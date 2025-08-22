<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{

    protected $fillable = ['purchase_id', 'product_id', 'brand', 'category', 'unit', 'unit_price', 'quantity', 'total_price'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
