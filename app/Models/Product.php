<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'compare_at_price',
        'image_url',
        'net_weight',
        'category',
        'stock',
        'is_active',
        'is_featured',
        'is_promo',
        'is_free_shipping',
    ];

    protected $casts = [
        'price' => 'integer',
        'compare_at_price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_promo' => 'boolean',
        'is_free_shipping' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function formattedPrice(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(?string $value): ?string
    {
        return $this->normalizeImageUrl($value);
    }

    public function normalizeImageUrl(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = trim((string) $value);

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/storage/')) {
            return $value;
        }

        if (str_starts_with($value, 'localhost/storage/')) {
            return '/storage/' . Str::after($value, 'localhost/storage/');
        }

        if (str_contains($value, '/storage/')) {
            return '/storage/' . Str::after($value, '/storage/');
        }

        if (str_starts_with($value, 'products/')) {
            return '/storage/' . $value;
        }

        return $value;
    }
}
