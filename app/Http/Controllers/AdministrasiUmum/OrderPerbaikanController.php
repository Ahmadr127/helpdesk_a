<?php

namespace App\Http\Controllers\AdministrasiUmum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderPerbaikan;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderPerbaikanStatusUpdated;

class OrderPerbaikanController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderPerbaikan::with(['creator', 'location']);

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor', 'like', "%{$request->search}%")
                  ->orWhere('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_inventaris', 'like', "%{$request->search}%")
                  ->orWhere('nip_peminta', 'like', "%{$request->search}%");
            });
        }

        // Date range filter
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('administrasi-umum.order-perbaikan._table', compact('orders'))->render(),
                'pagination' => $orders->links()->toHtml()
            ]);
        }

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders', 
            'totalOrders', 
            'pendingOrders', 
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }

    public function show(OrderPerbaikan $orderPerbaikan)
    {
        $orderPerbaikan->load(['creator', 'location', 'history.creator']);
        return view('administrasi-umum.order-perbaikan.show', compact('orderPerbaikan'));
    }

    public function updateStatus(Request $request, OrderPerbaikan $orderPerbaikan)
    {
        $request->validate([
            'status' => 'required|in:konfirmasi,reject',
            'nama_penanggung_jawab' => 'required|string',
            'follow_up' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $orderPerbaikan->update([
                'status' => $request->status,
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'follow_up' => $request->follow_up,
                'updated_by' => auth()->id()
            ]);

            // Add history entry
            $orderPerbaikan->history()->create([
                'status' => $request->status,
                'follow_up' => $request->follow_up,
                'created_by' => auth()->id(),
            ]);

            // Send notification to user
            $orderPerbaikan->creator->notify(new OrderPerbaikanStatusUpdated($orderPerbaikan));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status order berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve(Request $request, OrderPerbaikan $orderPerbaikan)
    {
        try {
            DB::beginTransaction();

            $orderPerbaikan->update([
                'status' => OrderPerbaikan::STATUS_KONFIRMASI,
                'updated_by' => auth()->id()
            ]);

            $orderPerbaikan->history()->create([
                'status' => OrderPerbaikan::STATUS_KONFIRMASI,
                'follow_up' => 'Order disetujui dan dikonfirmasi',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('administrasi-umum.order-perbaikan.index')
                ->with('success', 'Order berhasil disetujui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('administrasi-umum.order-perbaikan.index')
                ->with('error', 'Terjadi kesalahan saat menyetujui order: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, OrderPerbaikan $orderPerbaikan)
    {
        try {
            DB::beginTransaction();

            $orderPerbaikan->update([
                'status' => OrderPerbaikan::STATUS_REJECT,
                'updated_by' => auth()->id()
            ]);

            $orderPerbaikan->history()->create([
                'status' => OrderPerbaikan::STATUS_REJECT,
                'follow_up' => $request->follow_up ?? 'Order ditolak',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('administrasi-umum.order-perbaikan.index')
                ->with('success', 'Order berhasil ditolak');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('administrasi-umum.order-perbaikan.index')
                ->with('error', 'Terjadi kesalahan saat menolak order: ' . $e->getMessage());
        }
    }

    public function complete(Request $request, OrderPerbaikan $orderPerbaikan)
    {
        try {
            DB::beginTransaction();

            $orderPerbaikan->update([
                'status' => 'completed',
                'updated_by' => auth()->id()
            ]);

            $orderPerbaikan->history()->create([
                'status' => 'completed',
                'follow_up' => $request->follow_up ?? 'Order telah selesai',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('administrasi-umum.order-perbaikan.index')
                ->with('success', 'Order berhasil diselesaikan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('administrasi-umum.order-perbaikan.index')
                ->with('error', 'Terjadi kesalahan saat menyelesaikan order: ' . $e->getMessage());
        }
    }

    public function start(OrderPerbaikan $orderPerbaikan)
    {
        DB::beginTransaction();
        try {
            $orderPerbaikan->update([
                'status' => 'in_progress',
                'updated_by' => Auth::id(),
                'started_at' => now()
            ]);

            $orderPerbaikan->history()->create([
                'status' => 'in_progress',
                'follow_up' => 'Pengerjaan order dimulai',
                'created_by' => Auth::id()
            ]);

            $orderPerbaikan->creator->notify(new OrderPerbaikanStatusUpdated($orderPerbaikan));

            DB::commit();
            return redirect()->back()->with('success', 'Order berhasil dimulai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = OrderPerbaikan::with(['creator', 'location']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        // Add export logic here (e.g., Excel or PDF generation)
        
        return back()->with('success', 'Export dimulai. File akan segera tersedia.');
    }

    public function urgent()
    {
        $query = OrderPerbaikan::with(['creator', 'location'])
            ->where('prioritas', 'URGENT');

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        $orders = $query->latest()->paginate(10);

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }

    public function segera()
    {
        $query = OrderPerbaikan::with(['creator', 'location'])
            ->where('prioritas', 'SEGERA');

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        $orders = $query->latest()->paginate(10);

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }

    public function biasa()
    {
        $query = OrderPerbaikan::with(['creator', 'location'])
            ->where('prioritas', 'BIASA');

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        $orders = $query->latest()->paginate(10);

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }

    public function pending()
    {
        $query = OrderPerbaikan::with(['creator', 'location'])
            ->where('status', OrderPerbaikan::STATUS_PENDING);

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        $orders = $query->latest()->paginate(10);

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }

    public function rejected()
    {
        $query = OrderPerbaikan::with(['creator', 'location'])
            ->where('status', OrderPerbaikan::STATUS_REJECT);

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        $orders = $query->latest()->paginate(10);

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }

    public function confirmed()
    {
        $query = OrderPerbaikan::with(['creator', 'location'])
            ->where('status', OrderPerbaikan::STATUS_KONFIRMASI);

        // Get statistics data
        $totalOrders = OrderPerbaikan::count();
        $pendingOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_PENDING)->count();
        $rejectedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_REJECT)->count();
        $confirmedOrders = OrderPerbaikan::where('status', OrderPerbaikan::STATUS_KONFIRMASI)->count();

        // Get priority statistics
        $urgentOrders = OrderPerbaikan::where('prioritas', 'URGENT')->count();
        $segeraOrders = OrderPerbaikan::where('prioritas', 'SEGERA')->count();
        $biasaOrders = OrderPerbaikan::where('prioritas', 'BIASA')->count();

        $orders = $query->latest()->paginate(10);

        return view('administrasi-umum.order-perbaikan.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'urgentOrders',
            'segeraOrders',
            'biasaOrders'
        ));
    }
} 