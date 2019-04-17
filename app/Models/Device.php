<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;
    protected $table = 'device';
    public function getName($value='')
    {
        return $this->brand;
    }
    public function getFitments(){
        return Fitment::where(function ($query) {
            $query->where("brand",$this->brand);
        });
    }
    public function getFuntions(){
        return Funtion::where(function ($query) {
            $query->where("brand",$this->brand);
            $query->whereRaw("find_in_set('{$this->brand}/{$this->brand_set}',`brand_set`)");
            $query->whereRaw("find_in_set('{$this->dload}',`dload`)");
            $query->whereRaw("find_in_set('{$this->speedup}',`speedup`)");
        });
    }
}
