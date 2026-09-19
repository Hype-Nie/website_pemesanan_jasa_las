<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'custom_order_id',
        'payment_type',
        'amount',
        'payment_method',
        'proof_image_path',
        'bank_name',
        'account_name',
        'status',
        'verified_at',
        'admin_notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the custom order this payment belongs to.
     */
    public function customOrder(): BelongsTo
    {
        return $this->belongsTo(CustomOrder::class);
    }

    /**
     * Check if payment is Down Payment.
     */
    public function isDownPayment(): bool
    {
        return $this->payment_type === 'down_payment';
    }

    /**
     * Check if payment is Full/Final Payment.
     */
    public function isFullPayment(): bool
    {
        return $this->payment_type === 'full_payment';
    }

    /**
     * Get readable label for payment type.
     */
    public function typeLabel(): string
    {
        return match ($this->payment_type) {
            'full_payment' => 'Pelunasan',
            default => 'Uang Muka (DP)',
        };
    }

    /**
     * Get badge color class for payment type.
     */
    public function typeBadgeClass(): string
    {
        return match ($this->payment_type) {
            'full_payment' => 'success',
            default => 'warning text-dark',
        };
    }
}
