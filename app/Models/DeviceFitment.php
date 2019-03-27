<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceFitment extends Model
{
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
    protected $table = 'device_fitment';
    protected $casts = [
        'querystr' => 'array',
    ];
    public function device()
    {
        return $this->belongsTo(Device::class, 'did')
            ->select(['id','brand','brand_set','dload','floor']);
    }
}
