<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceFitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_fitment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('did')->comment('设备ID');
            $table->string('name')->comment('装饰项目名称');
            $table->string('stuff')->comment('材料');
            $table->string('spec')->comment('规格编号');
            $table->string('unit')->comment('单位');
            $table->float('price')->comment('单价');
            $table->string('desc')->comment('描述');
            $table->tinyInteger('has_in_base')->comment('是否标配/是否在基础价格包含');
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
        Schema::dropIfExists('device_fitment');
    }
}
