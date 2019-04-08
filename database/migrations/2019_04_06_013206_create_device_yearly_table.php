<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceYearlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_yearly', function (Blueprint $table) {
            $table->increments('id');
            $table->string('province_id')->comment('省')->nullable();
            $table->string('city_id')->comment('市')->nullable();
            $table->string('district_id')->comment('区')->nullable();
            $table->string('explain')->comment('计算说明')->nullable();
            $table->string('desc')->comment('备注')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_yearly');
    }
}
