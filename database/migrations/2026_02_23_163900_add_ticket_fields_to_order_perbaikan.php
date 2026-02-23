<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_perbaikan', function (Blueprint $table) {
            // Tambah kolom nip_peminta
            if (!Schema::hasColumn('order_perbaikan', 'nip_peminta')) {
                $table->string('nip_peminta')->nullable()->after('nama_peminta');
            }

            // Tambah kolom baru seperti tiket
            if (!Schema::hasColumn('order_perbaikan', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('lokasi');
            }
            if (!Schema::hasColumn('order_perbaikan', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('department_id');
            }
            if (!Schema::hasColumn('order_perbaikan', 'building_id')) {
                $table->unsignedBigInteger('building_id')->nullable()->after('category_id');
            }
        });

        // Tambah foreign keys setelah kolom ditambahkan
        Schema::table('order_perbaikan', function (Blueprint $table) {
            // Cek apakah foreign key sudah ada sebelum menambahkan
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('building_id')->references('id')->on('buildings')->nullOnDelete();
        });

        // PostgreSQL: jadikan kolom existing nullable menggunakan raw SQL
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "nama_barang" DROP NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "unit_proses" DROP NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "unit_proses_name" DROP NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "unit_penerima" DROP NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "nama_peminta" DROP NOT NULL');
        // Untuk enum jenis_barang: hapus constraint lama dulu, lalu jadikan nullable
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "jenis_barang" DROP NOT NULL');
    }

    public function down(): void
    {
        Schema::table('order_perbaikan', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['building_id']);
            $table->dropColumn(['department_id', 'category_id', 'building_id']);

            if (Schema::hasColumn('order_perbaikan', 'nip_peminta')) {
                $table->dropColumn('nip_peminta');
            }
        });

        // Restore NOT NULL constraints
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "nama_barang" SET NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "unit_proses" SET NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "unit_proses_name" SET NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "unit_penerima" SET NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "nama_peminta" SET NOT NULL');
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "jenis_barang" SET NOT NULL');
    }
};
