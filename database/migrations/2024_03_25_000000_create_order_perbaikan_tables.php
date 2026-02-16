<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table for order perbaikan
        Schema::create('order_perbaikan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->datetime('tanggal');
            $table->string('unit_proses');
            $table->string('unit_penerima');
            $table->string('nip_peminta');
            $table->string('kode_inventaris');
            $table->string('nama_barang');
            $table->string('lokasi');
            $table->string('jenis_barang');
            $table->text('keluhan');
            $table->enum('prioritas', ['BIASA', 'SEGERA', 'URGENT']);
            $table->enum('status', ['pending', 'konfirmasi', 'reject'])->default('pending');
            $table->text('follow_up')->nullable();
            $table->string('nama_penanggung_jawab')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table for order perbaikan history
        Schema::create('order_perbaikan_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_perbaikan_id')->constrained('order_perbaikan')->onDelete('cascade');
            $table->string('status');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_perbaikan_history');
        Schema::dropIfExists('order_perbaikan');
    }
}; 