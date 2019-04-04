<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ElevatorPrice extends Model
{
    use SoftDeletes;

    protected $table = 'elevator_price';
    protected $fillable=['eid',
        '设备基价','设备超米费','设备运输费','设备功能加价','设备装修选项', '设备税率','设备税率计算','功能按项目计价',
        '设备非标单价','设备临时用梯费','设备合计',
        '安装基价','安装超米费','政府验收费','贯通门增加安装价','安装非标单价','安装临时用梯费','安装税率','安装税率计算',
        '二次验收费用','安装合计',
        'desc',
        ];
    public function elevator(){
        return $this->belongsTo(Elevator::class, 'eid');
    }
    public function sumDevicePrice(){
        return $this->设备基价
        +$this->设备超米费+$this->设备功能加价+$this->设备装修选项
            +$this->设备运输费+$this->设备非标单价+$this->设备临时用梯费;
    }
    function runExpe($fouce=false){//重新计算填入
        $ele=$this->elevator;
        $ep=$ele->expe;
        if($ele->status==Elevator::STATUS_ANS && !$fouce){
            //已审非强制计算直接返回
            return;
        }
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
            '功能按项目计价'=>0,

            '安装基价'=>$dev->install_price,
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
                $expe['设备功能加价']+=$func->price*$func->pivot->num;
                if($func->unit=='项目'){
                    $expe['功能按项目计价']+=$func->price*$func->pivot->num;
                }
            }
        }
        //计算装修
        $expe['设备装修选项']=0;
        foreach ($ele->fitment as $fitment){
            if($fitment->has_in_base!=1){
                $expe['设备装修选项']+=$fitment->price*$fitment->pivot->num;
            }
        }
        //设备税率计算
        if($ep && $ep->设备税率>0){
            $expe['设备税率计算']=((
                $expe['设备基价']+$expe['设备超米费']
                +$expe['设备功能加价']
                +$expe['设备装修选项']
                +$expe['设备运输费']
                +$ep->设备非标单价
                +$ep->设备临时用梯费
            )/(1+$dev->device_rate))*(1+$ep->设备税率);
            $expe['设备税率计算']=round($expe['设备税率计算'],0);
        }
        //设备合计 设备税率计算*设备数
        if($ep && $ep->设备税率>0){
            $expe['设备合计']=$expe['设备税率计算']*$ele->num;
            if($ele->num>1 && $expe['功能按项目计价']>0){
                $dy_money=$expe['功能按项目计价']*($ele->num-1);
                $expe['设备合计']=$expe['设备合计']-((($dy_money)/(1+$dev->device_rate))*(1+$ep->设备税率));
            }
            $expe['设备合计']=round($expe['设备合计'],0);
        }
        //安装税率计算
        if($ep && $ep->安装税率>0){
            $expe['安装税率计算']=((
                        $expe['安装基价']+$expe['安装超米费']
                        +$ep->政府验收费
                        +$ep->贯通门增加安装价
                        +$ep->安装临时用梯费
                        +$ep->安装非标单价
                        +$ep->二次验收费用
                    )/(1+$dev->install_rate))*(1+$ep->安装税率);
            $expe['设备税率计算']=round($expe['设备税率计算'],0);
        }
        //安装合计 安装税率计算*设备数
        if($ep && $ep->安装税率>0){
            $expe['安装合计']=$expe['安装税率计算']*$ele->num;
            $expe['安装合计']=round($expe['安装合计'],0);
        }
        if($ep){
            $ep->update($expe);
        }else{
            self::insert($expe);
        }

    }
}
