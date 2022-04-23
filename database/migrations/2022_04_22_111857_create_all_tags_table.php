<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_tags', function (Blueprint $table) {
            $table->id("all_tag_id");
            $table->string("value", 255);
            $table->enum('type', ['tag', 'category', 'sub_variation', 'sub_category', 'variation', 'name', 'desc'])->default('tag');
        });
    }

    // type and value or individual color, size and quantity

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_tags');
    }
}
