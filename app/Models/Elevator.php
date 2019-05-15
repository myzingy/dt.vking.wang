<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elevator extends Model
{
    const STATUS_NEW=0;         //未提交
    const STATUS_YTJ=10;         //乙方地方提审
    const STATUS_SBS=20;         //甲方地方设计审核
    const STATUS_ANS=30;         //甲方地方成本审核
    const STATUS_YJ_SBS=40;      //乙方集团设计审核
    const STATUS_YJ_ANS=50;      //乙方集团成本审核
    const STATUS_JD_JT=60;       //甲方地方提审项目到集团
    const STATUS_JJ_SBS=70;      //甲方集团设计审核
    const STATUS_JJ_ANS=80;      //甲方集团成本审核
    const STATUS=[
        self::STATUS_NEW=>'未提交',
        self::STATUS_YTJ=>'乙方地方提审',
        self::STATUS_SBS=>'甲方地方设计审核',
        self::STATUS_ANS=>'甲方地方成本审核',
        self::STATUS_YJ_SBS=>'乙方集团设计审核',
        self::STATUS_YJ_ANS=>'乙方集团成本审核',
        self::STATUS_JD_JT=>'甲方地方提审项目到集团',
        self::STATUS_JJ_SBS=>'甲方集团设计审核',
        self::STATUS_JJ_ANS=>'甲方集团成本审核'
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
        //return $this->belongsToMany(Funtion::class, 'elevator_func','eid','fid')->withPivot('num');
        return $this->hasMany(ElevatorFunc::class, 'eid');
    }
    public function fitment()
    {
        //return $this->belongsToMany(Fitment::class, 'elevator_fitment','eid','fid')->withPivot('num');
        return $this->hasMany(ElevatorFitment::class, 'eid');
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
