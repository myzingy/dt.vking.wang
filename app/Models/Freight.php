<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Freight extends Model
{
    protected $table = 'freight';
    use SoftDeletes;
    public function getDloadAttribute($value)
    {
        if(is_string($value))
            return explode(',',$value);
        return [];
    }
    public function setDloadAttribute($value)
    {
        $this->attributes['dload'] = implode(',',$value);
    }
    public function getFloorAttribute($value)
    {
        if(is_string($value))
            return explode(',',$value);
        return [];
    }
    public function setFloorAttribute($value)
    {
        $this->attributes['floor'] = implode(',',$value);
    }
    public function getBrandSetAttribute($value)
    {
        if(is_string($value))
            return explode(',',$value);
        return [];
    }
    public function setBrandSetAttribute($value)
    {
        $this->attributes['brand_set'] = implode(',',$value);
    }
}
