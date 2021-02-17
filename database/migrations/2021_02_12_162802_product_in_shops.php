<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductInShops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_in_shops', function (Blueprint $table) {
            $table->id();
            $table->string('in_shop_product_id');
            $table->unsignedSmallInteger('shop_id');
            $table->unsignedBigInteger('card_id');
            $table->timestamps();

            $table
                ->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('card_id')
                ->references('id')
                ->on('cards')
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
