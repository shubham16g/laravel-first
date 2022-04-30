<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectFilterSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connect_filter_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('sub_category_id')->references('sub_category_id')->on('sub_categories');
            $table->unsignedBigInteger('filter_structure_id');
            $table->foreign('filter_structure_id')->references('filter_structure_id')->on('filter_structures');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_applicable')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connect_filter_sub_categories');
    }
}
