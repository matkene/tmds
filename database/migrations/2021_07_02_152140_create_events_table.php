<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('tags')->nullable();
            $table->mediumText('image');
            $table->unsignedInteger('created_by');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location');
            $table->mediumText('guest')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('events');
    }
}
