<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class productImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Product::updateOrCreate([
                'code' => $row[0],
                'name' => $row[1]
            ],[
                'benhead' => $row[2],
                'created_user'     => 1,
                'updated_user'     => 1,
            ]);
        }
    }
}

