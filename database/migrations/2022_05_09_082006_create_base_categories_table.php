<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *̥
     * @return void
     */
    public function up()
    {
        Schema::create('base_categories', function (Blueprint $table) {
            $table->id('base_category_id');
            $table->string('name', 255);
            $table->string('icon', 255);
            $table->string('image', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_categories');
    }
}
