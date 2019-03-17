<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceFreightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_freight', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('did')->comment('设备ID');
            $table->string('from')->comment('发货地点');
            $table->string('to')->comment('到货地点');
            $table->float('price')->comment('单台价格');
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
        Schema::dropIfExists('device_freight');
    }
}
