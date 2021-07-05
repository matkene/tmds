<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('no_adults')->nullable();
            $table->integer('no_children')->nullable();
            $table->integer('no_infants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_attended')->default(true);
            $table->string('payment_status')->nullable();
            $table->string('date_visited')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->unsignedInteger('tour_id')->nullable();
            $table->foreign('tour_id')->references('id')->on('tours')
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
        Schema::dropIfExists('bookings');
    }
}
