<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElevatorFitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elevator_fitment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eid')->comment('电梯ID');
            $table->integer('fid')->comment('设备装修ID');
            $table->string('desc')->comment('描述');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elevator_fitment');
    }
}
