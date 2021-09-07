<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->mediumText('description');
            $table->string('image');
            $table->unsignedInteger('created_by');
            $table->string('location');
            $table->decimal('price')->default("0.00");
            $table->string('distance')->nullable();
            $table->string('ratings')->default("5.0");
            $table->boolean('is_active')->default(true);
            $table->integer('daily_limit')->nullable();
            $table->integer('infant_price')->default(0);
            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('tours');
    }
}
