<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_structures', function (Blueprint $table) {
            $table->id('filter_structure_id');
            $table->string('name', 100);
            $table->enum('input_type', ['text', 'text_all_cap', 'text_first_cap', 'decimal', 'integer', 'list_radio', 'list_checkbox']);
            $table->json('input_list')->nullable();
            $table->enum('filter_type', ['fixed', 'range', 'fixed_range'])->default('fixed');
            $table->string('postfix', 100)->nullable();
            $table->string('prefix', 100)->nullable();
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
        Schema::dropIfExists('filter_structures');
    }
}
