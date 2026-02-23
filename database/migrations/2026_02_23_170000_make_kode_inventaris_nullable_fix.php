<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // kode_inventaris was missed in previous nullable migration
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "kode_inventaris" DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE "order_perbaikan" ALTER COLUMN "kode_inventaris" SET NOT NULL');
    }
};
