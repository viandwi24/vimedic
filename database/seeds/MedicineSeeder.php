<?php

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Medicine::create([
            'name' => 'Panadol',
            'type' => 'capsules',
            'price' => 1000,
            'stock' => 40
        ]);
        Medicine::create([
            'name' => 'Bodrex',
            'type' => 'drops',
            'price' => 3000,
            'stock' => 50
        ]);
        Medicine::create([
            'name' => 'Example',
            'type' => 'injections',
            'price' => 5500,
            'stock' => 65
        ]);
    }
}
