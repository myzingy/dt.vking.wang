<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->increments('id');
            $table->string('brand')->comment('品牌');
            $table->string('load')->comment('载重');
            $table->float('speedup')->comment('提速');
            $table->integer('floor')->comment('楼层');
            $table->float('hoisting_height')->comment('标准提升高度');
            $table->string('brand_set')->comment('品牌系列');
            $table->integer('freeboard')->comment('超米费用/单价');
            $table->integer('device_rate')->comment('设备税率');
            $table->float('install_price')->comment('安装单价');
            $table->float('device_price')->comment('设备单价');
            $table->integer('install_rate')->comment('安装税率');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device');
    }
}
