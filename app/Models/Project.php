<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    const GABC=[
        'GA'=>'GA',
        'GB'=>'GB',
        'GC'=>'GC',
    ];
    use SoftDeletes;

    protected $table = 'project';

    public function elevators()
    {
        return $this->belongsToMany(Elevator::class,'project_elevator','pid','eid')
            ->select('elevator.*','project_elevator.num','project_elevator.id as id');
    }
    public function elevator()
    {
        return $this->hasMany(Elevator::class,'pid');
    }
    public static function getGABCStr($state){
        return self::GABC[$state]||'';
    }
    public function setOrientationAttribute($value)
    {
        $this->attributes['orientation'] = json_encode($value);
    }
    public function getOrientationAttribute($value)
    {
        return json_decode($value);
    }
}
