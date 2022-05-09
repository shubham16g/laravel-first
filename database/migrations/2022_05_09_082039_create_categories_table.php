<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name',255);
            $table->string('icon',255);
            $table->string('image',255);
            $table->unsignedBigInteger('base_category_id');
            $table->foreign('base_category_id')->references('base_category_id')->on('base_categories');

        });
    }

    /**
     * Reverse the migrations.
     *̥̥̥̥̥̥
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
