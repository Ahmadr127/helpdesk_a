<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class UserSettingsController extends Controller
{
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

    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $positions = $this->getPositions();
        
        return view('user.settings', [
            'user' => Auth::user(),
            'departments' => $departments,
            'positions' => $positions
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:15',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|exists:departments,code',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->position = $request->position;
        $user->department = $request->department;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
} 