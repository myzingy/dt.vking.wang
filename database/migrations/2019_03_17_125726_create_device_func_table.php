<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceFuncTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_func', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('did')->comment('设备ID');
            $table->string('name')->comment('功能名称');
            $table->string('price')->comment('功能加价');
            $table->string('has_in_base')->comment('是否标配/是否在基础价格包含');
            $table->string('desc')->comment('功能描述');
            $table->string('unit')->comment('功能单位');
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
        Schema::dropIfExists('device_func');
    }
}
