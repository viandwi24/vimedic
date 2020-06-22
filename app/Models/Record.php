<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = ['checkup','diagnosis', 'action', 'cost', 'patient_id', 'doctor_id', 'recipe_id', 'code'];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
