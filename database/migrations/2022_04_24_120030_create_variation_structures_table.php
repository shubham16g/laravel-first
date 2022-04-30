<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // need bool input_type, key
        Schema::create('variation_structures', function (Blueprint $table) {
            $table->id('variation_structure_id');
            $table->string('name', 100);
            $table->enum('input_type', ['string', 'string_all_cap', 'string_first_cap', 'numeric', 'integer']);
            $table->enum('extras', ['color', 'image'])->nullable();
            $table->json('input_list')->nullable();
            $table->enum('filter_type', ['fixed', 'range', 'fixed_range']);
            $table->string('postfix', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_structures');
    }
}
