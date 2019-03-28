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
        return $this->belongsToMany(DeviceFunc::class, 'device_func_rela','fid','did');
    }
    public function fitment()//装修
    {
        return $this->belongsToMany(DeviceFitment::class, 'device_fitment_rela','fid','did');
    }
    public function freight()//运费
    {

        $res=$this->belongsToMany(DeviceFreight::class, 'device_freight_rela','fid','did');
        return $res;
    }
    public function getFreight($to_province='',$to_city='',$to_district='')//运费
    {
        $where=[];
        if($to_province){
            $where['to_province']=$to_province;
        }
        if($to_city){
            $where['to_city']=$to_city;
        }
        if($to_district){
            $where['to_district']=$to_district;
        }
        $res=$this->hasOne(DeviceFreight::class, 'did');
        if(count($where)>0){
            $res->where($where);
        }
        return $res;
    }
    public function getName($value)
    {
        return $this->brand;
    }
}
