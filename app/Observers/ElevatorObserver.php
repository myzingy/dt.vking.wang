<?php

namespace App\Observers;

use App\Models\Elevator;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class ElevatorObserver
{
    /**
     * Handle the elevator "created" event.
     *
     * @param  \App\Elevator  $elevator
     * @return void
     */
    public function created(Elevator $elevator)
    {
        //
        if(!$elevator->did) return;
        $res=Device::find($elevator->did)->getFitments()->where('has_in_base',1)->get();
        if($res){
            $data=[];
            foreach ($res as $r){
                array_push($data,[
                    'eid'=>$elevator->id,
                    'fid'=>$r->id
                ]);
            }
            DB::table('elevator_fitment')->insert($data);
        }

        $res=Device::find($elevator->did)->getFuntions()->where('has_in_base',1)->get();
        if($res){
            $data=[];
            foreach ($res as $r){
                array_push($data,[
                    'eid'=>$elevator->id,
                    'fid'=>$r->id
                ]);
            }
            DB::table('elevator_func')->insert($data);
        }
    }

    /**
     * Handle the elevator "updated" event.
     *
     * @param  \App\Elevator  $elevator
     * @return void
     */
    public function updated(Elevator $elevator)
    {
        //
    }

    /**
     * Handle the elevator "deleted" event.
     *
     * @param  \App\Elevator  $elevator
     * @return void
     */
    public function deleted(Elevator $elevator)
    {
        //
    }

    /**
     * Handle the elevator "restored" event.
     *
     * @param  \App\Elevator  $elevator
     * @return void
     */
    public function restored(Elevator $elevator)
    {
        //
    }

    /**
     * Handle the elevator "force deleted" event.
     *
     * @param  \App\Elevator  $elevator
     * @return void
     */
    public function forceDeleted(Elevator $elevator)
    {
        //
    }
}
