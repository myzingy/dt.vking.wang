<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElevatorPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elevator_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eid')->nullable();
            $table->decimal('设备基价')->comment('设备基价')->nullable();
            $table->decimal('设备功能加价')->comment('功能加价')->nullable();
            $table->decimal('设备装修选项')->comment('装修选项')->nullable();
            $table->decimal('设备运输费')->comment('运输费')->nullable();
            $table->decimal('设备超米费')->comment('设备超米费')->nullable();
            $table->decimal('设备非标单价')->comment('非标单价')->nullable();
            $table->decimal('设备临时用梯费')->comment('临时用梯设备费')->nullable();
            $table->decimal('设备税率')->comment('设备税率')->nullable();
            $table->decimal('设备税率计算')->comment('设备税率计算')->nullable();
            $table->decimal('安装基价')->comment('安装基价')->nullable();
            $table->decimal('政府验收费')->comment('政府验收费')->nullable();
            $table->decimal('安装超米费')->comment('安装超米费')->nullable();
            $table->decimal('贯通门增加安装价')->comment('贯通门增加安装价')->nullable();
            $table->decimal('安装临时用梯费')->comment('安装临时用梯费')->nullable();
            $table->decimal('安装税率')->comment('安装税率')->nullable();
            $table->decimal('安装税率计算')->comment('安装税率计算')->nullable();
            $table->string('desc')->comment('备注');
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
        Schema::dropIfExists('elevator_price');
    }
}
