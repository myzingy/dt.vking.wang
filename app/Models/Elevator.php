<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elevator extends Model
{
    use SoftDeletes;

    protected $table = 'elevator';

    public function project()
    {
        return $this->belongsTo(Project::class, 'pid');
    }
    public function device()
    {
        return $this->belongsTo(Device::class, 'did')
            ->select(['id','brand','brand_set','dload','floor']);
    }
    public function func()
    {
        return $this->belongsToMany(DeviceFunc::class, 'elevator_func','eid','fid');
    }
    public function fitment()
    {
        return $this->belongsToMany(DeviceFitment::class, 'elevator_fitment','eid','fid');
    }
}
