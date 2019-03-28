<?php

namespace App\Observers;

use App\Models\DeviceFitment;

class DeviceFitmentObserver
{
    /**
     * Handle the device fitment "created" event.
     *
     * @param  \App\DeviceFitment  $deviceFitment
     * @return void
     */
    public function created(DeviceFitment $deviceFitment)
    {
        //
    }

    /**
     * Handle the device fitment "updated" event.
     *
     * @param  \App\DeviceFitment  $deviceFitment
     * @return void
     */
    public function updated(DeviceFitment $deviceFitment)
    {
        //
    }

    /**
     * Handle the device fitment "deleted" event.
     *
     * @param  \App\DeviceFitment  $deviceFitment
     * @return void
     */
    public function deleted(DeviceFitment $deviceFitment)
    {
        //
        DB::table('device_fitment_rela')->where('fid',$deviceFitment->id)->delete();
    }

    /**
     * Handle the device fitment "restored" event.
     *
     * @param  \App\DeviceFitment  $deviceFitment
     * @return void
     */
    public function restored(DeviceFitment $deviceFitment)
    {
        //
    }

    /**
     * Handle the device fitment "force deleted" event.
     *
     * @param  \App\DeviceFitment  $deviceFitment
     * @return void
     */
    public function forceDeleted(DeviceFitment $deviceFitment)
    {
        //
    }
}
