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
            $table->enum('input_type', ['string', 'string_all_cap', 'string_first_cap', 'numeric', 'integer', 'bool']);
            $table->json('input_list')->nullable();
            $table->enum('filter_type', ['fixed', 'range', 'fixed_range'])->default('fixed');
            $table->string('postfix', 100)->nullable();
            $table->string('prefix', 100)->nullable();
            $table->boolean('is_multiple_input')->default(false);
            $table->boolean('is_required');
            $table->boolean('is_applicable');
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
