<?php
/**
 *
 */
function getBrand(){
    if(Admin::user()->isAdministrator()){
        return '*';
    }
    if(Admin::user()->can('甲方集团') || Admin::user()->can('甲方地方')){
        return '*';
    }
    $p=[];
    foreach (Admin::user()->permissions as $per){
        if(strpos($per->name,'电梯')>-1){
            array_push($p,$per->slug);
        }
    }
    return $p;
}

function getCity(){
    if(Admin::user()->isAdministrator()){
        return '*';
    }
    if(Admin::user()->can('甲方集团') || Admin::user()->can('乙方集团')){
        return '*';
    }
    $p=[];
    foreach (Admin::user()->permissions as $per){
        if(strpos($per->name,'城市')>-1){
            array_push($p,$per->slug);
        }
    }
    return $p;
}

function getStatus($nowStatus=0,$type='device'){
    $maxStatus=0;

    if(Admin::user()->isAdministrator()){
        $maxStatus=\App\Models\Elevator::STATUS_JJ_ANS;
    }else{
        if(Admin::user()->can('乙方地方')){
            $maxStatus=\App\Models\Elevator::STATUS_YTJ;
        }
        if(Admin::user()->can('甲方地方')){
            $maxStatus=$type=='device'?\App\Models\Elevator::STATUS_SBS:\App\Models\Elevator::STATUS_ANS;
        }
        if(Admin::user()->can('乙方集团')){
            $maxStatus=$type=='device'?\App\Models\Elevator::STATUS_YJ_SBS:\App\Models\Elevator::STATUS_YJ_ANS;
        }
        if(Admin::user()->can('甲方集团')){
            $maxStatus=$type=='device'?\App\Models\Elevator::STATUS_JJ_SBS:\App\Models\Elevator::STATUS_JJ_ANS;
        }
    }
    return $nowStatus+10>$maxStatus?$nowStatus:$nowStatus+10;
}
function deviceName($device){
    $device=json_decode(json_encode($device), true);
    return '[ID:'.$device['id'].']'
        .' 品牌:'.$device['brand'].'/'.$device['brand_set']
        .'，载重:'.$device['dload']
        .'，速度:'.$device['speedup']
        .'，层站:'.$device['floor'];
}