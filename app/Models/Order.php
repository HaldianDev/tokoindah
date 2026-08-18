<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Add this line
use App\Models\User;
use App\Models\OrderStatusHistory;

class Order extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    // ---------------------------------------------------------------------
    // Status constants (enum‑like)
    // ---------------------------------------------------------------------
    public const STATUS_PENDING          = 'pending';
    public const STATUS_WAITING_PAYMENT  = 'waiting_payment';
    public const STATUS_VERIFYING_PAYMENT = 'verifying_payment';
    public const STATUS_PENDING_DP       = 'pending_dp';
    public const STATUS_PERSUYAAN        = 'persetujuan';
    public const STATUS_ADMIN_APPROVED   = 'admin_approved';
    public const STATUS_OWNER_PENDING_DP = 'owner_pending_dp';
    public const STATUS_ANGSURAN_BERJALAN = 'angsuran_berjalan';
    public const STATUS_COMPLETED        = 'completed';
    public const STATUS_REJECTED         = 'rejected';
    public const STATUS_PROCESSING       = 'processing';
    public const STATUS_SHIPPED          = 'shipped';
    public const STATUS_CANCELED         = 'canceled';

    /**
     * Relationship to status histories.
     */
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /**
     * Change order status and store a history record atomically.
     */
    public function changeStatus(string $newStatus, ?User $actor = null, ?string $notes = null): void
    {
        DB::transaction(function () use ($newStatus, $actor, $notes) {
            $this->update(['status' => $newStatus]);
            $this->statusHistories()->create([
                'status'  => $newStatus,
                'user_id' => $actor?->id,
                'notes'   => $notes,
            ]);
        });
    }

    protected $fillable = [
        'uuid', // Add uuid to fillable
        'order_number',
        'user_id',
        'payment_method',
        'credit_tenor_months',
        'down_payment',
        'monthly_installment',
        'total_amount',
        'status',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'notes',
        'is_offline',
        'ktp_path',
        'shipping_cost',
        'total_weight',
        'payment_proof_path',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function getStatusNameCustomerAttribute()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_WAITING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_VERIFYING_PAYMENT => 'Verifikasi Pembayaran',
            self::STATUS_PROCESSING => 'Diproses', // You may need to add this constant
            self::STATUS_SHIPPED => 'Dikirim', // You may need to add this constant
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELED => 'Dibatalkan', // You may need to add this constant
            self::STATUS_PENDING_DP => 'Menunggu DP',
            self::STATUS_PERSUYAAN => 'Persetujuan',
            self::STATUS_ADMIN_APPROVED => 'Disetujui Admin',
            self::STATUS_OWNER_PENDING_DP => 'Menunggu Persetujuan DP',
            self::STATUS_ANGSURAN_BERJALAN => 'Angsuran Berjalan',

        ][$this->status] ?? 'Tidak Diketahui';
    }

    public function getStatusClassCustomerAttribute()
    {
        return [
            self::STATUS_PENDING => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            self::STATUS_WAITING_PAYMENT => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            self::STATUS_VERIFYING_PAYMENT => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
            self::STATUS_PROCESSING => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
            self::STATUS_SHIPPED => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
            self::STATUS_COMPLETED => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
            self::STATUS_REJECTED => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
            self::STATUS_CANCELED => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
            self::STATUS_PENDING_DP => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            self::STATUS_PERSUYAAN => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            self::STATUS_ADMIN_APPROVED => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
            self::STATUS_OWNER_PENDING_DP => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            self::STATUS_ANGSURAN_BERJALAN => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        ][$this->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
