<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['code', 'status', 'total_price', 'note', 'doctor_id'];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'recipe_medicine')->withPivot('stock', 'price');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}