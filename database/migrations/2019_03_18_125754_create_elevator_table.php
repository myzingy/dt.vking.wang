<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElevatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elevator', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->comment('项目ID');
            $table->integer('did')->comment('电梯设备ID');
            $table->integer('num')->comment('电梯数量');
            $table->float('height')->comment('提升高度');
            $table->string('layer_number')->comment('层/站/门数');
            $table->string('stop_sign')->comment('停站标记');
            $table->string('base_floor')->comment('基站楼层');
            $table->string('standard')->comment('标准');
            $table->string('drive_mode')->comment('驱动方式');
            $table->string('control_mode')->comment('控制方式');
            $table->string('fire_switch')->comment('消防返回开关在');
            $table->string('stop_switch')->comment('暂停服务开关在');
            $table->string('control_function')->comment('控制功能');
            $table->integer('pit_depth')->comment('底坑深度mm');
            $table->integer('top_height')->comment('顶层高度mm');
            $table->string('well_structure')->comment('井道结构');
            $table->string('well_fixation')->comment('井道支架固定');
            $table->integer('hall_width')->comment('厅门尺寸（mm）-宽度');
            $table->integer('hall_height')->comment('厅门尺寸（mm）-高度');
            $table->integer('car_width')->comment('轿厢尺寸（mm）-宽度');
            $table->integer('car_height')->comment('轿厢尺寸（mm）-高度');
            $table->integer('car_depth')->comment('轿厢尺寸（mm）-深度');
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
        Schema::dropIfExists('elevator');
    }
}
