<?php

namespace App\Observers;

use App\Models\DeviceFreight;

class DeviceFreightObserver
{
    /**
     * Handle the device freight "created" event.
     *
     * @param  \App\DeviceFreight  $deviceFreight
     * @return void
     */
    public function created(DeviceFreight $deviceFreight)
    {
        //
    }

    /**
     * Handle the device freight "updated" event.
     *
     * @param  \App\DeviceFreight  $deviceFreight
     * @return void
     */
    public function updated(DeviceFreight $deviceFreight)
    {
        //
    }

    /**
     * Handle the device freight "deleted" event.
     *
     * @param  \App\DeviceFreight  $deviceFreight
     * @return void
     */
    public function deleted(DeviceFreight $deviceFreight)
    {
        //
        DB::table('device_freight_rela')->where('fid',$deviceFreight->id);
    }

    /**
     * Handle the device freight "restored" event.
     *
     * @param  \App\DeviceFreight  $deviceFreight
     * @return void
     */
    public function restored(DeviceFreight $deviceFreight)
    {
        //
    }

    /**
     * Handle the device freight "force deleted" event.
     *
     * @param  \App\DeviceFreight  $deviceFreight
     * @return void
     */
    public function forceDeleted(DeviceFreight $deviceFreight)
    {
        //
    }
}
