<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elevator extends Model
{
    const STATUS_NEW=0;    //未提交
    const STATUS_YTJ=1;    //已提交
    const STATUS_SBS=2;    //设备已审
    const STATUS_ANS=3;    //安装已审
    const STATUS=[
        self::STATUS_NEW=>'未提交',
        self::STATUS_YTJ=>'已提交',
        self::STATUS_SBS=>'设备已审',
        self::STATUS_ANS=>'安装已审',
    ];

    use SoftDeletes;

    protected $table = 'elevator';

    public function project()
    {
        return $this->belongsTo(Project::class, 'pid');
    }
    public function device()
    {
        return $this->belongsTo(Device::class, 'did');
    }
    public function func()
    {
        return $this->belongsToMany(DeviceFunc::class, 'elevator_func','eid','fid')->withPivot('num');
    }
    public function fitment()
    {
        return $this->belongsToMany(Fitment::class, 'elevator_fitment','eid','fid')->withPivot('num');
    }
    public function expe(){
        return $this->hasOne(ElevatorPrice::class,'eid');
    }
    public static function getStatusStr($state=self::STATUS_NEW){
        if(empty(self::STATUS[$state])){
            return self::STATUS[self::STATUS_NEW];
        }
        return self::STATUS[$state];
    }
}
