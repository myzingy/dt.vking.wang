<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;
    protected $table = 'device';

    public function func()//功能
    {
        return $this->hasOne(DeviceFunc::class, 'did');
    }
    public function fitment()//装修
    {
        return $this->hasOne(DeviceFitment::class, 'did');
    }
    public function freight()//运费
    {
        return $this->hasOne(DeviceFreight::class, 'did');
    }
    public function getName($value)
    {
        return $this->brand;
    }
}
