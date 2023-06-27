<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasUUID, SoftDeletes;

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = ['product_id', 'subject', 'user_id', 'rating', 'comment'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
