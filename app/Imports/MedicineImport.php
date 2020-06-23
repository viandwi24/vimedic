<?php

namespace App\Imports;

use App\Models\Medicine;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MedicineImport implements ToModel, WithHeadingRow
{
    public $acc_type = ['capsules', 'tablet', 'liquid', 'drops', 'injections'];
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $name = isset($row['name']) ? @$row['name'] : Str::random(5);
        $type = isset($row['type'])
            ? (in_array($row['type'], $this->acc_type) ? $row['type'] : 'capsules')
            : 'capsules';
        $price = isset($row['price']) ? intval(@$row['price']) : 0;
        $stock = isset($row['stock']) ? intval(@$row['stock']) : 1;
        
        return new Medicine([
            'name' => $name,
            'type' => $type,
            'price' => $price,
            'stock' => $stock,
        ]);
    }
}
