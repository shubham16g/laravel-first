<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id('sub_category_id');
            $table->string('name', 100);
            $table->string('desc', 255);
            $table->string('type', 100)->nullable();
            $table->json('type_values')->nullable();

            $table->unsignedBigInteger('variation_structure');
            $table->foreign('variation_structure')->references('variation_structure_id')->on('variation_structures');

            $table->unsignedBigInteger('sub_variation_structure')->nullable();
            $table->foreign('sub_variation_structure')->references('sub_variation_structure_id')->on('sub_variation_structures');

            $table->boolean('is_group_variations')->default(true);
            $table->boolean('is_show_variation_as_product')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
    }
}
