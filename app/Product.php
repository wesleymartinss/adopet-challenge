<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    /**
     * @var array
     */
    private static $whiteListFilter = [
        'name',
        'description',
        'categorie',
        'price',
        'stock_quantity'
    ];
    protected $fillable = ['name', 'description', 'categorie', 'price', 'stock_quantity'];

    public function scopeMinPrice(Builder $query, $value): Builder {
        return $query->where('price', '>=' , $value);
    }

    public function scopeMaxPrice(Builder $query, $value): Builder {
        return $query->where('price', '<=' , $value);
    }

}
