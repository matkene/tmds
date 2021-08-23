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
            $table->increments('id');
            $table->integer('no_adults');
            $table->integer('no_children');
            $table->integer('no_infants');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_attended')->default(false);
            $table->string('payment_status')->default("Pending"); //interface for this
            $table->timestamp('date_of_visit')->nullable();
            $table->string('ticket_no');
            $table->string('payment_request_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('tour_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
