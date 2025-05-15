<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    protected $fillable = ['name', 'category_id', 'description', 'price', 'image'];
    use HasFactory;
    use HasUlids;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
