<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMileagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mileages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->mediumInteger('opening_mileage')->nullable();
            $table->mediumInteger('closing_mileage')->nullable();
            $table->smallInteger('partner_id');
            $table->smallInteger('location_id_start');
            $table->smallInteger('location_id_end');
            $table->mediumInteger('personal_travel_at_start')->nullable();
            $table->mediumInteger('personal_travel_at_end')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

   
}
