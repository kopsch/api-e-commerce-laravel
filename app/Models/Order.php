<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasUUID, SoftDeletes;

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = ['product_id', 'name', 'user_id', 'status', 'order_date'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
