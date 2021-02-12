<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ZOTAC GAMING GEFORCE RTX 3060 Ti Twin Edge
            $table->unsignedSmallInteger('chipset_id');
            $table->boolean('available')->default(false);
            $table->timestamps();

            $table
                ->foreign('chipset_id')
                ->references('id')
                ->on('chipsets')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
