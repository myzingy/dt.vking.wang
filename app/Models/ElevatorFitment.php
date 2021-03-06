<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElevatorFitment extends Model
{
    protected $table = 'elevator_fitment';

    public $timestamps = false;
    protected $fillable = ['fid','eid','has_in_base','price','unit','desc','name','stuff','spec'];

}
