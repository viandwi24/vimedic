<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('doctor_id')->unsigned();
            $table->string('code')->unique();
            $table->longText('note')->nullable();
            $table->integer('total_price');
            $table->enum('status', ['already_taken', 'not_yet_taken'])->default('not_yet_taken');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
