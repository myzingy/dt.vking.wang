<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElevatorPrice extends Model
{
    use SoftDeletes;

    protected $table = 'elevator_price';
    public function elevator(){
        return $this->belongsTo(Elevator::class, 'eid');
    }
    function reExpe(){//重新计算填入
        $ele=$this->elevator;
        $pj=$ele->project;
        $dev=$ele->device;
        $chaomi=$ele->height-$dev->hoisting_height*$dev->floor;
        $expe=[
            'eid'=>$this->eid,
            '设备基价'=>$dev->device_price,
            '设备超米费'=>0,
            '设备运输费'=>0,
            '设备功能加价'=>0,
            '设备装修选项'=>0,

            '安装超米费'=>0,
        ];
        if($chaomi>0){
            $expe['设备超米费']=$chaomi*$dev->freeboard_dev;
            $expe['安装超米费']=$chaomi*$dev->freeboard_ins;
        }
        //设备运输费
        $fids=[];
        $dfr=DB::table('device_freight_rela')->where('did',$ele->did)->get();
        foreach($dfr as $d){
            array_push($fids,$d->fid);
        }
        $freight=\App\Models\DeviceFreight::where([
            'to_province'=>$pj->province_id,
            'to_city'=>$pj->city_id,
        ])->whereIn('id',$fids)->first();
        if($freight){
            $expe['设备运输费']=$freight->price;
        }
        //计算功能
        $expe['设备功能加价']=0;
        foreach ($ele->func as $func){
            if($func->has_in_base!=1){
                $expe['设备功能加价']+=$func->price*$func->num;
            }
        }
        //计算装修
        $expe['设备装修选项']=0;
        foreach ($ele->fitment as $fitment){
            if($fitment->has_in_base!=1){
                $expe['设备装修选项']+=$fitment->price*$fitment->num;
            }
        }
        //设备税率计算
        if($this->设备税率>0){
            $expe['设备税率计算']=((
                $expe['设备基价']+$expe['设备超米费']
                +$expe['设备功能加价']
                +$expe['设备装修选项']
                +$expe['设备运输费']
                +$expe['设备非标单价']
                +$expe['设备临时用梯费']
            )/(1+$dev->device_rate))*(1+$this->设备税率);
        }
    }
}
