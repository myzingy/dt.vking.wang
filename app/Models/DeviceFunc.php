<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceFunc extends Model
{
    use SoftDeletes;
    const UNIT_MEN=1;
    const UNIT_CENG=2;
    const UNIT_ZHAN=3;
    const UNIT_BU=4;
    const UNIT_MI=5;
    const UNIT_GE=6;

    const UNIT=[
        self::UNIT_MEN=>'门',
        self::UNIT_CENG=>'层',
        self::UNIT_ZHAN=>'站',
        self::UNIT_BU=>'部',
        self::UNIT_MI=>'米',
        self::UNIT_GE=>'个',
    ];
    protected $table = 'device_func';
    public function device()
    {
        return $this->belongsTo(Device::class, 'did')
            ->select(['id','brand','brand_set','dload','speedup','hoisting_height']);
    }
    public static function getUnitStr($state=self::UNIT_BU){
        if(empty(self::UNIT[$state])){
            return self::UNIT[self::UNIT_BU];
        }
        return self::UNIT[$state];
    }
}
