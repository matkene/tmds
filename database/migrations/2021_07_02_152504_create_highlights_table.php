<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHighlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highlights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->mediumText('image');
            $table->mediumText('video');
            $table->string('slug');
            $table->mediumText('description')->nullable();
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('highlights');
    }
}
