<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectElevator extends Model
{
    protected $table = 'project_elevator';
    public $timestamps = false;
    protected $fillable = ['pid','eid','num'];

//    public function elevator()
//    {
//        return $this->hasMany(Elevator::class,'id','eid');
//    }

}
