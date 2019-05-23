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