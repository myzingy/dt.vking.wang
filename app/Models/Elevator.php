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
        return $this->hasOne(Device::class, 'did');
    }
    public function funcs()
    {
        return $this->hasMore(ElevatorFunc::class, 'did');
    }
    public function fitments()
    {
        return $this->hasMore(ElevatorFitment::class, 'did');
    }
}
