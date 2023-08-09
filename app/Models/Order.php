<?php

namespace App\Models;

use App\Models\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'uuid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'integer',
        'quantity' => 'integer',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $order) {
            $order->order_no = $order->generateOrderNo();
        });
    }

    public function generateOrderNo(): string
    {
        $invoiceNo = rand(10000000, 99999999);

        while (
            self::query()
            ->where('order_no', $invoiceNo)
            ->exists()
        ) {
            $this->generateOrderNo();
        }

        return $invoiceNo;
    }

    /** @return Attribute<float, void> */
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount / 100
        );
    }
}
