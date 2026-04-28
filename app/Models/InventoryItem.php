<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'minimarket_id',
        'product_id',
        'quantity',
        'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'last_updated' => 'datetime',
        ];
    }

    public function minimarket(): BelongsTo
    {
        return $this->belongsTo(Minimarket::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity < $this->product->min_stock_threshold;
    }
}
