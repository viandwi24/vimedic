<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Record;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Record::class, function (Faker $faker) {
    return [
        'patient_id' => 1,
        'recipe_id' => 2,
        'doctor_id' => 3,
        'checkup' => 'test4',
        'diagnosis' => 'test2',
        'action' => 'test1',
        'cost' => 1000,
        'check_date' => '01/01/2020',
        'code' => Str::random(10) . Carbon::now()->timestamp
    ];
});
