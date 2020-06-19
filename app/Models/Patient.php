<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'identity_number', 'birth', 'address'];
    protected $casts = ['birth' => 'date'];

    public function getBirthAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setBirthAttribute($value)
    {
        $date = explode('/', $value);
        $this->attributes['birth'] = Carbon::createFromDate($date[2], $date[1], $date[0]);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
