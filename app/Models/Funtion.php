<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funtion extends Model
{
    protected $table = 'funtion';
    use SoftDeletes;
    const UNIT=[
        '台'=>'台',
        '层'=>'层',
        '樘'=>'樘',
        '个'=>'个',
        '项目'=>'项目',
    ];
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
    public function getSpeedupAttribute($value)
    {
        if(is_string($value))
            return explode(',',$value);
        return [];
    }
    public function setSpeedupAttribute($value)
    {
        $this->attributes['speedup'] = implode(',',$value);
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
