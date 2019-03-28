<?php

namespace App\Observers;

use App\Models\DeviceFunc;
use Illuminate\Support\Facades\DB;

class DeviceFuncObserver
{
    /**
     * Handle the device func "created" event.
     *
     * @param  \App\DeviceFunc  $deviceFunc
     * @return void
     */
    public function created(DeviceFunc $deviceFunc)
    {
        //
    }

    /**
     * Handle the device func "updated" event.
     *
     * @param  \App\DeviceFunc  $deviceFunc
     * @return void
     */
    public function updated(DeviceFunc $deviceFunc)
    {
        //
    }

    /**
     * Handle the device func "deleted" event.
     *
     * @param  \App\DeviceFunc  $deviceFunc
     * @return void
     */
    public function deleted(DeviceFunc $deviceFunc)
    {
        //
        DB::table('device_func_rela')->where('fid',$deviceFunc->id);
    }

    /**
     * Handle the device func "restored" event.
     *
     * @param  \App\DeviceFunc  $deviceFunc
     * @return void
     */
    public function restored(DeviceFunc $deviceFunc)
    {
        //
    }

    /**
     * Handle the device func "force deleted" event.
     *
     * @param  \App\DeviceFunc  $deviceFunc
     * @return void
     */
    public function forceDeleted(DeviceFunc $deviceFunc)
    {
        //
    }
}
