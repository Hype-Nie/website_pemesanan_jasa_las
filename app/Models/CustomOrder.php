<?php

namespace App\Models;

use App\Enums\WorkProgress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CustomOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_code',
        'user_id',
        'catalog_product_id',
        'product_name',
        'description',
        'dimensions',
        'material_preference',
        'quantity',
        'reference_design_path',
        'total_price',
        'dp_amount',
        'status',
        'progress_percentage',
        'progress_notes',
        'progress_photo_path',
        'admin_notes',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['status_badge'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'dp_amount' => 'decimal:2',
            'quantity' => 'integer',
            'progress_percentage' => 'integer',
        ];
    }

    /**
     * Boot the model and register creating event.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CustomOrder $order) {
            if (empty($order->order_code)) {
                $order->order_code = 'ORD-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Get the Bootstrap badge class based on order status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_production' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the catalog product associated with this order.
     */
    public function catalogProduct(): BelongsTo
    {
        return $this->belongsTo(CatalogProduct::class);
    }

    /**
     * Get the payments for this order.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the latest payment for this order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Get WorkProgress enum instance for this order.
     */
    public function workProgress(): WorkProgress
    {
        return WorkProgress::tryFrom((int) $this->progress_percentage) ?? WorkProgress::PENDING;
    }

    /**
     * Get required Down Payment (DP) amount. Default to 50% of total_price if not set.
     */
    public function requiredDpAmount(): float
    {
        if ($this->dp_amount !== null && (float)$this->dp_amount > 0) {
            return (float) $this->dp_amount;
        }

        return round((float) ($this->total_price ?? 0) * 0.5, 2);
    }

    /**
     * Calculate total verified payment amount.
     */
    public function totalPaid(): float
    {
        return (float) $this->payments()->where('status', 'verified')->sum('amount');
    }

    /**
     * Check if Down Payment is verified.
     */
    public function isDpPaid(): bool
    {
        $hasVerifiedDp = $this->payments()
            ->where(function ($q) {
                $q->where('payment_type', 'down_payment')
                  ->orWhereNull('payment_type');
            })
            ->where('status', 'verified')
            ->exists();

        if ($hasVerifiedDp) {
            return true;
        }

        if ((float) ($this->total_price ?? 0) <= 0) {
            return $this->payments()->where('status', 'verified')->exists();
        }

        return $this->totalPaid() >= $this->requiredDpAmount();
    }

    /**
     * Check if there is a pending DP payment waiting for admin verification.
     */
    public function hasPendingDpPayment(): bool
    {
        return $this->payments()
            ->where('payment_type', 'down_payment')
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if there is a pending full payment waiting for admin verification.
     */
    public function hasPendingFullPayment(): bool
    {
        return $this->payments()
            ->where('payment_type', 'full_payment')
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Calculate remaining unpaid balance.
     */
    public function remainingBalance(): float
    {
        $total = (float) ($this->total_price ?? 0);
        return max(0, $total - $this->totalPaid());
    }

    /**
     * Check if order is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return (float) ($this->total_price ?? 0) > 0 && $this->remainingBalance() <= 0;
    }
}
