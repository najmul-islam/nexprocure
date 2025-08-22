<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['supplier_id', 'order_no', 'date', 'notes', 'total_amount', 'paid_amount', 'status'];


    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
