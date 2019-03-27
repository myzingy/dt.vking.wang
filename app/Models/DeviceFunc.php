<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceFunc extends Model
{
    use SoftDeletes;
    const UNIT=[
        '台'=>'台',
        '层'=>'层',
        '樘'=>'樘',
        '个'=>'个',
        '项目'=>'项目',
    ];
    protected $table = 'device_func';
    protected $casts = [
        'querystr' => 'array',
    ];
    public function device()
    {
        $device=$this->belongsTo(Device::class, 'did')
            ->select(['id','brand','brand_set','dload','floor']);
        return $device;
    }
    public static function getUnitStr($state=self::UNIT_BU){
        if(empty(self::UNIT[$state])){
            return self::UNIT[self::UNIT_BU];
        }
        return self::UNIT[$state];
    }
}
