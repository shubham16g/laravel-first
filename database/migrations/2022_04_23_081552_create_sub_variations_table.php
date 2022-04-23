<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_variations', function (Blueprint $table) {
            $table->id('sub_variation_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->string('sub_variation',255);
            $table->float('price');
            $table->float('mrp');
            $table->enum('status', ['active', 'disabled', 'deleted', 'out_of_stock', 'limited_stock', 'coming_soon'])->default('active');
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
        Schema::dropIfExists('sub_variations');
    }
}
