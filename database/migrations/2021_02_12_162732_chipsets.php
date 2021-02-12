<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Chipsets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chipsets', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name'); // GEFORCE RTX 3060 Ti
            $table->string('slug'); // geforce_rtx_3060_ti
            $table->unsignedTinyInteger('gpubrand_id'); // 1 nvidia 2 amd
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
        //
    }
}
