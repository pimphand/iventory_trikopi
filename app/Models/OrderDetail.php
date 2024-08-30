<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = ['items'];
    function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_details_id', 'id');
    }
}
