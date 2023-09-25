<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property Collection|Product[] $products
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }
}
