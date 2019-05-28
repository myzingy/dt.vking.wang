<?php

namespace App\Providers;

use App\Models\Elevator;
use App\Observers\ElevatorObserver;
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
        Elevator::observe(ElevatorObserver::class);
        \Illuminate\Database\Query\Builder::macro('sql', function () {
            $bindings = $this->getBindings();
            $sql = str_replace('?', '"%s"', $this->toSql());
            return sprintf($sql, ...$bindings);
        });
    }
}
