<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from')->comment('发货地')->nullable();
            $table->string('to')->comment('到货地')->nullable();
            $table->decimal('price')->comment('运费')->nullable();
            $table->string('brand')->comment('品牌')->nullable();
            $table->string('dload')->comment('载重')->nullable();
            $table->string('floor')->comment('楼层')->nullable();
            $table->string('brand_set')->comment('系列')->nullable();
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
        Schema::dropIfExists('freight');
    }
}
