<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\FindByFieldType;


class HbsBenhead extends Model
{
    use FindByFieldType;
    public $table = 'hbs_benhead';
    protected static $table_static = 'hbs_benhead';
    protected $guarded = ['id'];
    
    
    protected $casts = [
        
    ];
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }
        return parent::castAttribute($key, $value);
    }
}
