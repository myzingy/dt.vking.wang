<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElevatorFunc extends Model
{
    protected $table = 'elevator_func';

    public $timestamps = false;
    protected $fillable = ['fid','eid','has_in_base','price','unit','desc','name'];

}
