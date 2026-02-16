<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderPerbaikan;
use App\Models\OrderPerbaikanItem;
use App\Models\OrderPerbaikanHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class AdministrasiUmumController extends Controller
{
    public function orderBarang(Request $request)
    {
        $query = OrderPerbaikan::with(['creator'])
            ->where('created_by', auth()->id())
            ->where('status', 'pending');

        // Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('user.administrasi-umum.order-perbaikan._table', compact('orders'))->render()
            ]);
        }

        $locations = Location::all();
        return view('user.administrasi-umum.order-barang', compact('orders', 'locations'));
    }

    public function orderBarangKonfirmasi(Request $request)
    {
        $query = OrderPerbaikan::with(['creator'])
            ->where('created_by', auth()->id())
            ->where('status', 'konfirmasi');

        // Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('user.administrasi-umum.order-perbaikan._table', compact('orders'))->render()
            ]);
        }

        return view('user.administrasi-umum.order-barang-konfirmasi', compact('orders'));
    }

    public function orderBarangReject(Request $request)
    {
        $query = OrderPerbaikan::with(['creator'])
            ->where('created_by', auth()->id())
            ->where('status', 'reject');

        // Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('user.administrasi-umum.order-perbaikan._table', compact('orders'))->render()
            ]);
        }

        return view('user.administrasi-umum.order-barang-reject', compact('orders'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'nomor' => 'required|unique:order_perbaikan,nomor',
            'tanggal' => 'required|date',
            'unit_proses' => 'required',
            'unit_penerima' => 'required',
            'no_penerima' => 'required',
            'items' => 'required|array|min:1',
            'items.*.kode_inventaris' => 'required',
            'items.*.nama_barang' => 'required',
            'items.*.lokasi' => 'required',
            'items.*.jenis_barang' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $order = OrderPerbaikan::create([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'unit_proses' => $request->unit_proses,
                'unit_penerima' => $request->unit_penerima,
                'no_penerima' => $request->no_penerima,
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $order->items()->create($item);
            }

            // Create initial history
            $order->history()->create([
                'status' => 'pending',
                'keterangan' => 'Order dibuat',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order berhasil dibuat',
                'order' => $order->load('items'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateOrder(Request $request, OrderPerbaikan $order)
    {
        $request->validate([
            'nomor' => 'required|unique:order_perbaikan,nomor,' . $order->id,
            'tanggal' => 'required|date',
            'unit_proses' => 'required',
            'unit_penerima' => 'required',
            'no_penerima' => 'required',
            'items' => 'required|array|min:1',
            'items.*.kode_inventaris' => 'required',
            'items.*.nama_barang' => 'required',
            'items.*.lokasi' => 'required',
            'items.*.jenis_barang' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $order->update([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'unit_proses' => $request->unit_proses,
                'unit_penerima' => $request->unit_penerima,
                'no_penerima' => $request->no_penerima,
                'updated_by' => Auth::id(),
            ]);

            // Delete existing items and replace with new ones
            $order->items()->delete();
            foreach ($request->items as $item) {
                $order->items()->create($item);
            }

            // Add history entry for update
            $order->history()->create([
                'status' => $order->status,
                'keterangan' => 'Order diperbarui',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order berhasil diperbarui',
                'order' => $order->load('items'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOrder(OrderPerbaikan $order)
    {
        return response()->json($order->load(['items', 'history.creator', 'creator']));
    }

    public function searchOrders(Request $request)
    {
        $query = OrderPerbaikan::with(['items', 'creator']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor', 'like', "%{$request->search}%")
                  ->orWhere('unit_proses', 'like', "%{$request->search}%")
                  ->orWhere('unit_penerima', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json($orders);
    }

    public function dokumen()
    {
        return view('user.administrasi-umum.dokumen');
    }

    public function formulir()
    {
        return view('user.administrasi-umum.formulir');
    }

    public function prosedur()
    {
        return view('user.administrasi-umum.prosedur');
    }

    public function index()
    {
        return view('user.administrasi-umum.index');
    }

    public function indexOrderPerbaikan()
    {
        $orders = OrderPerbaikan::where('created_by', auth()->id())
            ->latest()
            ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('user.administrasi-umum.order-perbaikan._table', compact('orders'))->render()
            ]);
        }

        return view('user.administrasi-umum.order-perbaikan.index', compact('orders'));
    }

    public function createOrderPerbaikan()
    {
        try {
            // Generate nomor otomatis sesuai format: OP/RTG/MTC-YYYYMMDD001
            $currentDate = now();
            $prefix = 'OP/RTG/MTC-' . $currentDate->format('Ymd');
            
            // Get the last order number for today
            $lastOrder = OrderPerbaikan::where('nomor', 'like', $prefix . '%')
                ->orderBy('nomor', 'desc')
                ->first();

            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->nomor, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $nomor = $prefix . $newNumber;
            $locations = Location::all();

            return view('user.administrasi-umum.order-barang-create', compact('nomor', 'locations'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeOrderPerbaikan(Request $request)
    {
        $validated = $request->validate([
            'nip_peminta' => 'required|string',
            'jenis_barang' => 'required|in:Inventaris,Non-Inventaris',
            'kode_inventaris' => 'required|string',
            'nama_barang' => 'required|string',
            'lokasi' => 'required|exists:locations,id',
            'keluhan' => 'required|string',
            'prioritas' => 'required|in:BIASA,SEGERA,URGENT',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor order with today's date
            $today = now();
            $prefix = 'OP/RTG/MTC-' . $today->format('Ymd');
            
            // Get the last order number for today
            $lastOrder = OrderPerbaikan::where('nomor', 'like', $prefix . '%')
                ->orderBy('nomor', 'desc')
                ->first();

            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->nomor, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $nomor = $prefix . $newNumber;

            $orderPerbaikan = OrderPerbaikan::create([
                'nomor' => $nomor,
                'tanggal' => $validated['tanggal'],
                'unit_proses' => 'RTG',
                'unit_penerima' => 'MTC',
                'nip_peminta' => $validated['nip_peminta'],
                'jenis_barang' => $validated['jenis_barang'],
                'kode_inventaris' => $validated['kode_inventaris'],
                'nama_barang' => $validated['nama_barang'],
                'lokasi' => $validated['lokasi'],
                'keluhan' => $validated['keluhan'],
                'prioritas' => $validated['prioritas'],
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);

            // Add history entry for the creation
            $orderPerbaikan->history()->create([
                'status' => 'pending',
                'keterangan' => 'Order dibuat',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order perbaikan berhasil dibuat dengan nomor: ' . $nomor,
                    'data' => $orderPerbaikan
                ]);
            }

            return redirect()
                ->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('success', 'Order perbaikan berhasil dibuat dengan nomor: ' . $nomor);

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat order: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat membuat order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showOrderPerbaikan(OrderPerbaikan $orderPerbaikan)
    {
        // Load the relationships we need
        $order = $orderPerbaikan->load(['history.creator', 'location', 'creator']);
        
        // TEMPORARY DEBUG LOGGING - Remove after fix
        \Log::info('===== ORDER ACCESS DEBUG =====');
        \Log::info('Order ID: ' . $order->id);
        \Log::info('Order created_by: ' . $order->created_by);
        \Log::info('Order created_by TYPE: ' . gettype($order->created_by));
        \Log::info('Current auth user ID: ' . auth()->id());
        \Log::info('Current auth user TYPE: ' . gettype(auth()->id()));
        \Log::info('Current auth user role: ' . auth()->user()->role);
        \Log::info('IDs match: ' . ($order->created_by === auth()->id() ? 'YES' : 'NO'));
        \Log::info('Is admin: ' . (auth()->user()->role === 'admin' ? 'YES' : 'NO'));
        \Log::info('Should allow: ' . (($order->created_by === auth()->id() || auth()->user()->role === 'admin') ? 'YES' : 'NO'));
        \Log::info('===============================');
        
        // Check if the current user is authorized to view this order
        // Admin can view all orders, regular users can only view their own
        if ($order->created_by !== auth()->id() && auth()->user()->role !== 'admin') {
            \Log::error('AUTHORIZATION FAILED - Returning 403');
            abort(403, 'Unauthorized action.');
        }

        \Log::info('AUTHORIZATION SUCCESS - Showing order');
        return view('user.administrasi-umum.order-perbaikan.show', compact('order'));
    }

    public function editOrderPerbaikan(OrderPerbaikan $orderPerbaikan)
    {
        // Check if the current user is authorized to edit this order
        // Admin can edit all orders, regular users can only edit their own
        if ($orderPerbaikan->created_by !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Check if the order is still editable (pending status)
        if ($orderPerbaikan->status !== 'pending') {
            return redirect()->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('error', 'Hanya order dengan status pending yang dapat diedit.');
        }

        $locations = Location::all();
        return view('user.administrasi-umum.order-perbaikan.edit', compact('orderPerbaikan', 'locations'));
    }

    public function updateOrderPerbaikan(Request $request, OrderPerbaikan $orderPerbaikan)
    {
        // Check if the current user is authorized to update this order
        // Admin can update all orders, regular users can only update their own
        if ($orderPerbaikan->created_by !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Check if the order is still editable (pending status)
        if ($orderPerbaikan->status !== 'pending') {
            return redirect()->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('error', 'Hanya order dengan status pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'nip_peminta' => 'required|string',
            'jenis_barang' => 'required|in:Inventaris,Non-Inventaris',
            'kode_inventaris' => 'required|string',
            'nama_barang' => 'required|string',
            'lokasi' => 'required|exists:locations,id',
            'keluhan' => 'required|string',
            'prioritas' => 'required|in:BIASA,SEGERA,URGENT',
        ]);

        try {
            DB::beginTransaction();

            $orderPerbaikan->update([
                'nip_peminta' => $validated['nip_peminta'],
                'jenis_barang' => $validated['jenis_barang'],
                'kode_inventaris' => $validated['kode_inventaris'],
                'nama_barang' => $validated['nama_barang'],
                'lokasi' => $validated['lokasi'],
                'keluhan' => $validated['keluhan'],
                'prioritas' => $validated['prioritas'],
                'updated_by' => auth()->id(),
            ]);

            // Add history entry for the update
            $orderPerbaikan->history()->create([
                'status' => $orderPerbaikan->status,
                'keterangan' => 'Order diperbarui',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('success', 'Order perbaikan berhasil diperbarui dengan nomor: ' . $orderPerbaikan->nomor);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui order: ' . $e->getMessage())
                ->withInput();
        }
    }
} 