<?php

namespace App\Providers;

use App\Models\DeviceFitment;
use App\Models\DeviceFreight;
use App\Models\DeviceFunc;
use App\Observers\DeviceFitmentObserver;
use App\Observers\DeviceFreightObserver;
use App\Observers\DeviceFuncObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        DeviceFunc::observe(DeviceFuncObserver::class);
        DeviceFitment::observe(DeviceFitmentObserver::class);
        DeviceFreight::observe(DeviceFreightObserver::class);
    }
}
