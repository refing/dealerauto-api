<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('vehicles', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     // $table->unsignedBigInteger('user_id');
        //     $table->string('name');
        //     $table->integer('year');
        //     $table->string('color');
        //     $table->BigInteger('price');
        //     $table->string('flagtype');
        //     $table->timestamps();
        //     $table->unsignedBigInteger('posted_by')->unsigned(); 
        //     $table->foreign('posted_by')
        //     ->references('_id')->on('users')
        //     ->onDelete('cascade');

        // });

        Schema::create('vehicles', function ($collection) {
            $collection->index('_id');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
