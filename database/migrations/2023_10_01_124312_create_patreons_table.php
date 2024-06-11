<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatreonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patreons', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // included->attributes->email:dgloria@freemail.hu
            $table->string('pledge_created'); //included->attributes->created:"2023-09-28T09:33:37.000+00:00"
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
        Schema::dropIfExists('patreons');
    }
}
