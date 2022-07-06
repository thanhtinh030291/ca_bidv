<?php

namespace App\Imports;

use App\TreatmentGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class treatmentImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) 
        {
            if($key == 0 ){
                continue;
            }
            TreatmentGroup::updateOrCreate([
                'treatment_group_name' => $row[0]
            ],[
                'type_max' => $row[1],
                'value_max' => $row[2],
                'ben_head_code' => empty($row[4]) ? null : $row[4],
                'created_user'     => 1,
                'updated_user'     => 1,
            ]);
        }
    }
}

