<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_item_id', 'quantity', 'price', 'notes'];

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
