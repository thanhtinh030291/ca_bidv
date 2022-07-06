<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class TreatmentGroup extends Model
{
    protected $table = 'treatment_group';
    protected static $table_static = 'treatment_group';
    protected $guarded = ['id'];
    
}
