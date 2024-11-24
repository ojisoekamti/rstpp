<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Add 'total_amount' to the $fillable array
    protected $fillable = ['total_amount', 'customer_name', 'table_id', 'phone', 'status'];

    // Or if you want to allow more fields, you can add them here as well
    // protected $fillable = ['total_amount', 'other_field', 'another_field'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }
}
