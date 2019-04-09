<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fitment extends Model
{
    protected $table = 'fitment';
    use SoftDeletes;
    const UNIT=[
        '面'=>'面',
        '台'=>'台',
        '个'=>'个',
        '道'=>'道',
        '侧'=>'侧',
        '套'=>'套',
        'mm'=>'mm',
    ];
    public function getBrandAttribute($value)
    {
        if(is_string($value))
            return explode(',',$value);
        return [];
    }
    public function setBrandAttribute($value)
    {
        $this->attributes['brand'] = implode(',',$value);
    }
}
