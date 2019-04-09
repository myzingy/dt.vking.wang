<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class FuntionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $res=DB::table('funtion')->get();
        foreach ($res as $r){
            $data['brand']='迅达';
            $querystr=json_decode($r->querystr);
            if($querystr->brand_set){
                $data['brand_set']=[];
                foreach ($querystr->brand_set as $bs){
                    if($bs){
                        array_push($data['brand_set'],$bs);
                    }
                }
                $data['brand_set']=implode(',',$data['brand_set']);
            }
            if($querystr->dload){
                $data['dload']=[];
                foreach ($querystr->dload as $bs){
                    if($bs){
                        array_push($data['dload'],$bs);
                    }
                }
                $data['dload']=implode(',',$data['dload']);
            }
            if($querystr->speedup){
                $data['speedup']=[];
                foreach ($querystr->speedup as $bs){
                    if($bs){
                        array_push($data['speedup'],$bs);
                    }
                }
                $data['speedup']=implode(',',$data['speedup']);
            }
            DB::table('funtion')
                ->where('id',$r->id)
                ->update($data);
        }
    }
}
