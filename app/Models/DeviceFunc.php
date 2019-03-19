<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceFunc extends Model
{
    use SoftDeletes;

    protected $table = 'device_func';
    public function device()
    {
        return $this->belongsTo(Device::class, 'did')->select(['id','brand','brand_set','dload','speedup','hoisting_height']);
    }
}
