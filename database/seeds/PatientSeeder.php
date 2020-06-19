<?php

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Patient::create([
            'name' => 'Alfian Dwi Nugraha',
            'identity_number' => 123019401278,
            'birth' => '24/04/2002',
            'address' => 'Jawa Timur, Kab. Mojokerto, Kec. Sooko, Ds. Kedung Maling'
        ]);
    }
}
