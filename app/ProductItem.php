<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_item_id');
    }
}
