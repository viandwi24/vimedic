<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'checkup','diagnosis', 'action', 'cost', 'patient_id',
        'doctor_id', 'recipe_id', 'code', 'check_date'
    ];
    protected $casts = ['check_date' => 'date'];

    public function getCheckDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setCheckDateAttribute($value)
    {
        $date = explode('/', $value);
        $this->attributes['check_date'] = Carbon::createFromDate($date[2], $date[1], $date[0]);
    }

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
