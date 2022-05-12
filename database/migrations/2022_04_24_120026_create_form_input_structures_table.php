<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormInputStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_input_structures', function (Blueprint $table) {
            $table->id('form_input_structure_id');
            $table->string('name', 100);
            $table->enum('input_type', ['string', 'numeric', 'integer', 'bool', 'email', 'phone', 'datetime', 'password', 'list', 'color', 'image']);
            $table->integer('input_max_length')->unsigned()->default(255);
            $table->integer('input_min_length')->unsigned()->default(0);
            $table->integer('input_max_lines')->unsigned()->default(2);
            $table->integer('input_min_lines')->unsigned()->default(1);
            $table->enum('string_capitalization', ['characters', 'sentences', 'words', 'none'])->default('none');
            $table->json('input_list')->nullable();
            $table->enum('filter_type', ['fixed', 'range', 'fixed_range'])->default('fixed');
            $table->string('suffix', 100)->nullable();
            $table->string('prefix', 100)->nullable();
            $table->boolean('is_multiple_input')->default(false);
            $table->boolean('is_required');
            // $table->boolean('is_applicable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_input_structures');
    }
}
