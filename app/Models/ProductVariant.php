<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'weight_value',
        'weight_unit',
        'unit',
        'pcs_per_dus',
        'min_stock_threshold',
        'image_path',
    ];

    protected $casts = [
        'weight_value' => 'float',
        'pcs_per_dus' => 'integer',
        'min_stock_threshold' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getFullNameAttribute()
    {
        return $this->product->name . ' (' . $this->weight_value . ' ' . $this->weight_unit . ')';
    }
}
