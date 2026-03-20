<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UserImportController extends Controller
{
    /**
     * Extract first and last name only (without middle names and titles)
     * Takes text before comma, then extracts first and last word
     */
    private function extractNameWithoutTitle($fullName)
    {
        // Get text before the first comma
        $parts = explode(',', $fullName);
        $name = trim($parts[0]);
        
        // Split by spaces and get first and last word
        $words = array_filter(explode(' ', $name));
        $words = array_values($words); // Re-index array
        
        if (count($words) == 0) {
            return '';
        } elseif (count($words) == 1) {
            return $words[0];
        } else {
            // Return first and last name only
            return $words[0] . ' ' . $words[count($words) - 1];
        }
    }

    public function showImportForm()
    {
        return view('admin.users_management.import');
    }

    public function import(Request $request)
    {
        try {
            // Validate file
            $validated = $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
                'set_as_head' => 'nullable|boolean',
            ]);

            $file = $request->file('file');
            
            if (!$file) {
                return redirect()->route('admin.users.import')
                    ->with('error', 'File tidak ditemukan');
            }

            $setAsHead = $request->boolean('set_as_head');

            DB::beginTransaction();

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            array_shift($rows);

            $imported = 0;
            $errors = [];
            
            // We do not need permissions/Role models anymore since we use 'user' or 'admin' string.
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $nik = trim($row[1] ?? '');
                    $name = trim($row[2] ?? '');
                    $organizationName = trim($row[3] ?? '');
                    $positionName = trim($row[4] ?? '');

                    if (!$nik || !$name) {
                        $errors[] = "Baris {$rowNumber}: NIK dan Nama wajib diisi";
                        continue;
                    }

                    // Find or auto-create department
                    $deptCode = null;
                    if ($organizationName) {
                        $department = Department::where('name', $organizationName)->first();
                        if (!$department) {
                            // Generate unique code from name
                            $baseCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $organizationName), 0, 10));
                            $code = $baseCode;
                            $counter = 1;
                            while (Department::where('code', $code)->exists()) {
                                $code = $baseCode . $counter++;
                            }
                            $department = Department::create([
                                'name'   => $organizationName,
                                'code'   => $code,
                                'status' => 1,
                            ]);
                        }
                        $deptCode = $department->code;
                    }

                    // Find or auto-create position
                    $posCode = null;
                    if ($positionName) {
                        $position = Position::where('name', $positionName)->first();
                        if (!$position) {
                            $baseCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $positionName), 0, 10));
                            $code = $baseCode;
                            $counter = 1;
                            while (Position::where('code', $code)->exists()) {
                                $code = $baseCode . $counter++;
                            }
                            $position = Position::create([
                                'name'   => $positionName,
                                'code'   => $code,
                                'status' => true,
                            ]);
                        }
                        $posCode = $position->code;
                    }


                    // Generate username from name (without titles)
                    $nameWithoutTitle = $this->extractNameWithoutTitle($name);
                    $username = strtolower(str_replace(' ', '.', preg_replace('/[^A-Za-z0-9\s]/', '', $nameWithoutTitle)));
                    $baseUsername = $username;
                    $counter = 1;

                    // Ensure username is unique (ignoring the current NIK's user)
                    while (User::where('email', $username)->where('nik', '!=', $nik)->exists()) {
                        $username = $baseUsername . $counter++;
                    }

                    // Create or update user
                    $user = User::where('nik', $nik)->first();

                    if ($user) {
                        $user->update([
                            'name'       => $name,
                            'email'      => $username,
                            'role'       => 'user',
                            'department' => $deptCode,
                            'position'   => $posCode,
                            'status'     => 1,
                        ]);
                    } else {
                        $user = User::create([
                            'nik'        => $nik,
                            'name'       => $name,
                            'email'      => $username,
                            'password'   => Hash::make('rsazra'),
                            'role'       => 'user',
                            'department' => $deptCode,
                            'position'   => $posCode,
                            'status'     => 1,
                            'phone'      => '-',
                        ]);
                    }

                    // Department and Positions don't have head_id in the current schema
                    // so we skip the Set as organization head logic.

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                }
            }

            DB::commit();

            if (!empty($errors)) {
                return redirect()->route('admin.users.import')
                    ->with('warning', "Import selesai dengan {$imported} user berhasil. Beberapa error: " . implode('; ', array_slice($errors, 0, 5)));
            }

            return redirect()->route('admin.users.index')
                ->with('success', "Berhasil mengimport {$imported} user");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.users.import')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.users.import')
                ->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['NO', 'NIP', 'Nama Karyawan', 'Organisasi', 'Posisi Pekerjaan', 'Jabatan'];
        $sheet->fromArray($headers, null, 'A1');

        $sampleData = [
            [1, '20141969', 'DIENI ANANDA PUTRI, DR., MARS', 'MUTU', 'MANAGER MUTU', 'MANAGER'],
            [2, '20061105', 'GARCINIA SATIVA FIZRIA SETIADI, Dr, MKM', 'PENUNJANG MEDIK', 'MANAGER PENUNJANG MEDIK', 'MANAGER'],
            [3, '20253017', 'INDRA THALIB, B.SN., MM', 'SDM', 'MANAGER SDM', 'MANAGER'],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        $headerStyle = $sheet->getStyle('A1:F1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        $filename = 'template_import_users.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
