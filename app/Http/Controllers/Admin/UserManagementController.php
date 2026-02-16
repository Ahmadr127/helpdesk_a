<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users_management.index', compact('users'));
    }

    public function getPositions()
    {
        return [
            'GENERAL' => 'GENERAL',
            'SDM - HRD' => 'SDM - HRD', 
            'PERAWAT (IGD)' => 'PERAWAT (IGD)',
            'Akses Bank Darah' => 'Akses Bank Darah',
            'GUDANG FARMASI' => 'GUDANG FARMASI',
            'PIUTANG PENJAMIN' => 'PIUTANG PENJAMIN',
            'KEU-AKTIVA TETAP' => 'KEU-AKTIVA TETAP',
            'KEU-KAS KECIL' => 'KEU-KAS KECIL',
            'KEU-PIUTANG KARTU KREDIT' => 'KEU-PIUTANG KARTU KREDIT',
            'IT' => 'IT',
            'Stock Opname' => 'Stock Opname',
            'MEDICAL CHECK UP' => 'MEDICAL CHECK UP',
            'PEMBELIAN FARMASI' => 'PEMBELIAN FARMASI',
            'PIUTANG PEGAWAI' => 'PIUTANG PEGAWAI',
            'HUTANG DOKTER' => 'HUTANG DOKTER',
            'GUDANG LOGISTIK' => 'GUDANG LOGISTIK',
            'REKAM MEDIS' => 'REKAM MEDIS',
            'PEMBELIAN LOGISTIK' => 'PEMBELIAN LOGISTIK',
            'KEU-AKUNTANSI' => 'KEU-AKUNTANSI',
            'DEPO' => 'DEPO',
            'MASTER DATA' => 'MASTER DATA',
            'RADIOLOGI' => 'RADIOLOGI',
            'LAPORAN UMUM' => 'LAPORAN UMUM',
            'PERAWAT (RAWAT JALAN)' => 'PERAWAT (RAWAT JALAN)',
            'HEMODIALISA' => 'HEMODIALISA',
            'MANAGEMENT' => 'MANAGEMENT',
            'LAPORAN ALL UNIT' => 'LAPORAN ALL UNIT',
            'LAPORAN' => 'LAPORAN',
            'LAPORAN DUTY MANAGER' => 'LAPORAN DUTY MANAGER',
            'DUTY MANAGER' => 'DUTY MANAGER',
            'KEPALA PENDAFTARAN' => 'KEPALA PENDAFTARAN',
            'LABORATORIUM' => 'LABORATORIUM',
            'FARMASI' => 'FARMASI',
            'INSTALASI GIZI' => 'INSTALASI GIZI',
            'KASIR' => 'KASIR',
            'RUANG (VK-BERSALIN)' => 'RUANG (VK-BERSALIN)',
            'CASEMIX' => 'CASEMIX',
            'RUANG (OK-BEDAH)' => 'RUANG (OK-BEDAH)',
            'DOKTER' => 'DOKTER',
            'Staff Keuangan Penagihan Perusahaan' => 'Staff Keuangan Penagihan Perusahaan',
            'PENDAFTARAN' => 'PENDAFTARAN',
            'REHABILITASI MEDIK' => 'REHABILITASI MEDIK',
            'PERAWAT (RAWAT INAP)' => 'PERAWAT (RAWAT INAP)',
            'SEKRETARIAT' => 'SEKRETARIAT',
        ];
    }

    public function create()
    {
        $positions = $this->getPositions();
        $departments = Department::where('status', 1)->get();
        return view('admin.users_management.create', compact('positions', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'role' => 'required|in:admin,user',
            'department' => 'required|exists:departments,code',
            'status' => 'required|boolean',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department'],
            'status' => (int)$request->status,
            'phone' => $request->phone,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $positions = $this->getPositions();
        $departments = Department::where('status', 1)->get();
        return view('admin.users_management.edit', compact('user', 'positions', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,user',
            'department' => 'required|exists:departments,code',
            'status' => 'required|boolean',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'department' => $validated['department'],
            'status' => (int)$validated['status'],
            'phone' => $validated['phone'],
            'position' => $validated['position']
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}