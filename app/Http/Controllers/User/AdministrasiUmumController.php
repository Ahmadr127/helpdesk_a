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
use App\Models\UnitProses;
use App\Models\Department;
use App\Models\Category;
use App\Models\Building;
use Illuminate\Support\Facades\Storage;

class AdministrasiUmumController extends Controller
{
    public function orderBarang(Request $request)
    {
        // Base query for in progress orders
        $inProgressQuery = OrderPerbaikan::with(['creator', 'category', 'department', 'location'])
            ->where('created_by', auth()->id())
            ->where('status', 'in_progress');

        // Add search for in progress orders
        if ($request->search) {
            $search = $request->search;
            $inProgressQuery->where(function($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        // Add date filter for in progress orders
        if ($request->start_date && $request->end_date) {
            $inProgressQuery->whereBetween('tanggal', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $inProgressOrders = $inProgressQuery->latest()->take(6)->get();

        // Get open orders for table
        $query = OrderPerbaikan::with(['creator', 'history', 'category', 'department', 'location'])
            ->where('created_by', auth()->id())
            ->where('status', 'open');

        // Add search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        // Filter tanggal for open orders
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
                'html' => view('user.administrasi-umum.order-perbaikan._table', compact('orders'))->render(),
                'inProgressHtml' => view('user.administrasi-umum.order-perbaikan._in_progress_cards', compact('inProgressOrders'))->render()
            ]);
        }

        return view('user.administrasi-umum.order-barang', compact('orders', 'inProgressOrders'));
    }

    public function orderBarangKonfirmasi(Request $request)
    {
        $query = OrderPerbaikan::with(['creator', 'history', 'category', 'department'])
            ->where('created_by', auth()->id())
            ->where('status', 'confirmed');

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
        $query = OrderPerbaikan::with(['creator', 'history', 'category', 'department'])
            ->where('created_by', auth()->id())
            ->where('status', 'rejected');

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
            'items.*.jenis_barang' => 'required|in:Inventaris,Umum',
        ]);

        try {
            DB::beginTransaction();

            $order = OrderPerbaikan::create([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'unit_proses' => $request->unit_proses,
                'unit_penerima' => $request->unit_penerima,
                'no_penerima' => $request->no_penerima,
                'status' => 'open',
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $order->items()->create($item);
            }

            // Create initial history
            $order->history()->create([
                'status' => 'open',
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
            'items.*.jenis_barang' => 'required|in:Inventaris,Umum',
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

    public function indexOrderPerbaikan(Request $request)
    {
        $status = $request->status ?? 'all';
        
        // Base query
        $query = OrderPerbaikan::with(['creator', 'history', 'category', 'department', 'location'])
            ->where('created_by', auth()->id());

        // Filter by status if specified
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Add search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics
        $stats = [
            'total' => OrderPerbaikan::where('created_by', auth()->id())->count(),
            'in_progress' => OrderPerbaikan::where('created_by', auth()->id())->where('status', 'in_progress')->count(),
            'confirmed' => OrderPerbaikan::where('created_by', auth()->id())->where('status', 'confirmed')->count(),
            'rejected' => OrderPerbaikan::where('created_by', auth()->id())->where('status', 'rejected')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('user.administrasi-umum.order-perbaikan._table', compact('orders'))->render()
            ]);
        }

        return view('user.administrasi-umum.order-perbaikan.index', compact('orders', 'status', 'stats'));
    }

    public function createOrderPerbaikan()
    {
        try {
            // Generate nomor otomatis sesuai format: OP/RTG/MTC-YYYYMMDD001
            $currentDate = now();
            $prefix = 'OP/RTG/MTC-' . $currentDate->format('Ymd');
            
            // Get the last order number for today, including soft deleted records
            $lastOrder = OrderPerbaikan::withTrashed()
                ->where('nomor', 'like', $prefix . '%')
                ->orderBy('nomor', 'desc')
                ->first();

            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->nomor, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $nomor = $prefix . $newNumber;
            $locations = Location::where('status', 1)->with('building')->orderBy('name', 'asc')->get();
            $unitProses = UnitProses::where('status', 1)->where('code', '!=', 'SIRS')->get();
            // Gunakan kategori SIRS — sama seperti form tiket
            $categories = Category::where('status', 1)
                ->whereHas('unitProses', function($q) {
                    $q->where('code', 'SIRS');
                })->get();

            // Get user department
            $user = auth()->user();
            $userDepartment = Department::where('code', $user->department)->first();

            return view('user.administrasi-umum.order-perbaikan.create', 
                compact('nomor', 'locations', 'unitProses', 'categories', 'userDepartment', 'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeOrderPerbaikan(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => ['required', 'exists:categories,id', function ($attribute, $value, $fail) {
                $category = Category::with('unitProses')->find($value);
                if (!$category || $category->unitProses?->code !== 'SIRS') {
                    $fail('Kategori yang dipilih harus kategori dari unit SIRS.');
                }
            }],
            'lokasi'           => 'required|exists:locations,id',
            'keluhan'          => 'required|string',
            'prioritas'        => 'required|in:RENDAH,SEDANG,TINGGI/URGENT',
            'tanggal'          => 'required|date',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            // Field opsional
            'nama_barang'      => 'nullable|string',
            'jenis_barang'     => 'nullable|in:Umum,Inventaris',
            'kode_inventaris'  => 'nullable|string',
            'unit_proses_code' => 'nullable|exists:unit_proses,code',
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor order
            $today = now();
            $prefix = 'OP/RTG/MTC-' . $today->format('Ymd');
            $lastOrder = OrderPerbaikan::withTrashed()
                ->where('nomor', 'like', $prefix . '%')
                ->orderBy('nomor', 'desc')
                ->first();
            $lastNumber = $lastOrder ? (int) substr($lastOrder->nomor, -3) : 0;
            $nomor = $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            // Get user & department
            $user = auth()->user();
            $userDepartment = Department::where('code', $user->department)->first();

            // Get location & building
            $location = Location::with('building')->findOrFail($validated['lokasi']);

            // Get category
            $category = Category::findOrFail($validated['category_id']);

            // Handle opt. unit proses
            $unitProsesData = null;
            if (!empty($validated['unit_proses_code'])) {
                $unitProsesData = UnitProses::where('code', $validated['unit_proses_code'])->first();
            }

            // Handle foto upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = 'order_' . time() . '_' . $foto->getClientOriginalName();
                $fotoPath = $foto->storeAs('order-photos', $filename, 'public');
            }

            $orderPerbaikan = OrderPerbaikan::create([
                'nomor'            => $nomor,
                'tanggal'          => $validated['tanggal'],
                'keluhan'          => $validated['keluhan'],
                'prioritas'        => $validated['prioritas'],
                'category_id'      => $category->id,
                'department_id'    => $userDepartment?->id,
                'lokasi'           => $location->id,
                'building_id'      => $location->building?->id,
                'nip_peminta'      => $user->nip,
                'nama_peminta'     => $user->name,
                // Opsional
                'nama_barang'      => $validated['nama_barang'] ?? null,
                'jenis_barang'     => $validated['jenis_barang'] ?? null,
                'kode_inventaris'  => $validated['kode_inventaris'] ?? null,
                'unit_proses'      => $unitProsesData?->code,
                'unit_proses_name' => $unitProsesData?->name,
                'unit_penerima'    => 'MTC',
                'foto'             => $fotoPath,
                'status'           => 'open',
                'created_by'       => auth()->id(),
            ]);

            $orderPerbaikan->history()->create([
                'status'     => 'open',
                'keterangan' => 'Order dibuat',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('success', 'Order perbaikan berhasil dibuat dengan nomor: ' . $nomor);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showOrderPerbaikan(OrderPerbaikan $orderPerbaikan)
    {
        // Load the relationships we need
        $order = $orderPerbaikan->load(['history.creator', 'location', 'creator']);
        
        // Check if the current user is authorized to view this order
        if ($order->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.administrasi-umum.order-perbaikan.show', compact('order'));
    }

    public function editOrderPerbaikan(OrderPerbaikan $orderPerbaikan)
    {
        if ($orderPerbaikan->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($orderPerbaikan->status !== 'open') {
            return redirect()->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('error', 'Hanya order dengan status open yang dapat diedit.');
        }

        $locations = Location::where('status', 1)->with('building')->orderBy('name', 'asc')->get();
        $unitProses = UnitProses::where('status', 1)->where('code', '!=', 'SIRS')->get();
        // Gunakan kategori SIRS — sama seperti form tiket
        $categories = Category::where('status', 1)
            ->whereHas('unitProses', function($q) {
                $q->where('code', 'SIRS');
            })->get();
        $userDepartment = Department::where('code', auth()->user()->department)->first();

        return view('user.administrasi-umum.order-perbaikan.edit',
            compact('orderPerbaikan', 'locations', 'unitProses', 'categories', 'userDepartment'));
    }

    public function updateOrderPerbaikan(Request $request, OrderPerbaikan $orderPerbaikan)
    {
        if ($orderPerbaikan->created_by !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        if ($orderPerbaikan->status !== 'open') {
            return response()->json(['success' => false, 'message' => 'Hanya order dengan status open yang dapat diedit.'], 422);
        }

        $validated = $request->validate([
            'category_id'      => ['required', 'exists:categories,id', function ($attribute, $value, $fail) {
                $category = Category::with('unitProses')->find($value);
                if (!$category || $category->unitProses?->code !== 'SIRS') {
                    $fail('Kategori yang dipilih harus kategori dari unit SIRS.');
                }
            }],
            'lokasi'           => 'required|exists:locations,id',
            'keluhan'          => 'required|string',
            'prioritas'        => 'required|in:RENDAH,SEDANG,TINGGI/URGENT',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            // Opsional
            'nama_barang'      => 'nullable|string',
            'jenis_barang'     => 'nullable|in:Umum,Inventaris',
            'kode_inventaris'  => 'nullable|string',
            'unit_proses_code' => 'nullable|exists:unit_proses,code',
        ]);

        try {
            DB::beginTransaction();

            $location = Location::with('building')->findOrFail($validated['lokasi']);

            // Handle opt. unit proses
            $unitProsesData = null;
            if (!empty($validated['unit_proses_code'])) {
                $unitProsesData = UnitProses::where('code', $validated['unit_proses_code'])->first();
            }

            // Handle foto
            $fotoPath = $orderPerbaikan->foto;
            if ($request->hasFile('foto')) {
                if ($orderPerbaikan->foto) {
                    Storage::disk('public')->delete($orderPerbaikan->foto);
                }
                $foto = $request->file('foto');
                $filename = 'order_' . time() . '_' . $foto->getClientOriginalName();
                $fotoPath = $foto->storeAs('order-photos', $filename, 'public');
            }

            $orderPerbaikan->update([
                'category_id'      => $validated['category_id'],
                'lokasi'           => $location->id,
                'building_id'      => $location->building?->id,
                'keluhan'          => $validated['keluhan'],
                'prioritas'        => $validated['prioritas'],
                'nama_barang'      => $validated['nama_barang'] ?? null,
                'jenis_barang'     => $validated['jenis_barang'] ?? null,
                'kode_inventaris'  => $validated['kode_inventaris'] ?? null,
                'unit_proses'      => $unitProsesData?->code,
                'unit_proses_name' => $unitProsesData?->name,
                'foto'             => $fotoPath,
                'updated_by'       => auth()->id(),
            ]);

            $orderPerbaikan->history()->create([
                'status'     => $orderPerbaikan->status,
                'keterangan' => 'Order diperbarui',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Order perbaikan berhasil diperbarui']);
            }

            return redirect()
                ->route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan)
                ->with('success', 'Order perbaikan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteOrderPerbaikan(OrderPerbaikan $orderPerbaikan)
    {
        // Check if the current user is authorized to delete this order
        if ($orderPerbaikan->created_by !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Check if the order can be deleted (only open status)
        if ($orderPerbaikan->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya order dengan status open yang dapat dihapus.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Delete related records first
            $orderPerbaikan->history()->delete();
            
            // Delete the photo if exists
            if ($orderPerbaikan->foto) {
                Storage::disk('public')->delete($orderPerbaikan->foto);
            }

            // Force delete the order (bypass soft delete)
            $orderPerbaikan->forceDelete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order perbaikan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus order: ' . $e->getMessage()
            ], 500);
        }
    }
}