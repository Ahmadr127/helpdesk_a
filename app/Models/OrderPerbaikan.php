<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPerbaikan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_perbaikan';

    const STATUS_PENDING = 'pending';
    const STATUS_KONFIRMASI = 'konfirmasi';
    const STATUS_REJECT = 'reject';

    protected $fillable = [
        'nomor',
        'tanggal',
        'unit_proses',
        'unit_penerima',
        'nip_peminta',
        'jenis_barang',
        'kode_inventaris',
        'nama_barang',
        'lokasi',
        'keluhan',
        'prioritas',
        'status',
        'follow_up',
        'nama_penanggung_jawab',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d H:i:s',
    ];

    // Relationships
    public function history()
    {
        return $this->hasMany(OrderPerbaikanHistory::class, 'order_perbaikan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'lokasi');
    }

    // Status helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isKonfirmasi()
    {
        return $this->status === self::STATUS_KONFIRMASI;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECT;
    }

    // Get status badge color
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_KONFIRMASI => 'bg-green-100 text-green-800',
            self::STATUS_REJECT => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Get status badge dot color
    public function getStatusDotClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-400',
            self::STATUS_KONFIRMASI => 'bg-green-400',
            self::STATUS_REJECT => 'bg-red-400',
            default => 'bg-gray-400',
        };
    }

    // Get formatted status text
    public function getStatusText()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_KONFIRMASI => 'Konfirmasi',
            self::STATUS_REJECT => 'Reject',
            default => 'Unknown'
        };
    }
} 