<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workblocks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('block_start');
            $table->dateTime('block_stop');
            $table->foreignId('workplace_id')->constrained();
            $table->integer('volunteer_number');
            $table->timestamps();
            $table->unique(['block_start', 'block_stop', 'workplace_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workblocks');
    }
};
