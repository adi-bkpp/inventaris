<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'category_id', 'name',
        'stock', 'price', 'photo',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
