<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->increments('id');
            $table->string('addr')->comment('归属地');
            $table->string('name')->comment('项目名称');
            $table->string('first_party')->comment('甲方名称');
            $table->string('artisan_man')->comment('技术负责人');
            $table->string('price_man')->comment('成本负责人');
            $table->string('desc')->comment('项目简介');
            $table->string('orientation')->comment('项目定位');
            $table->tinyInteger('status')->comment('状态');
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
        Schema::dropIfExists('project');
    }
}
