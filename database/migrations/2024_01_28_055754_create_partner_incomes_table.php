<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->nullable()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->double('income');
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
        Schema::dropIfExists('partner_incomes');
    }
}
