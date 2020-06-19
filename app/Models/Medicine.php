<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name', 'price', 'type', 'stock'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_medicine')->withPivot('stock', 'price');
    }
}
