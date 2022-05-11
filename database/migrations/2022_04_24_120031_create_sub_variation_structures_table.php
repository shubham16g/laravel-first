<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubVariationStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_variation_structures', function (Blueprint $table) {
            $table->id('sub_variation_structure_id');
            $table->string('name', 100);
            $table->enum('input_type', ['string', 'string_all_cap', 'string_first_cap', 'numeric', 'integer']);
            $table->json('input_list')->nullable();
            $table->enum('filter_type', ['fixed', 'range', 'fixed_range']);
            $table->string('postfix', 100)->nullable();
            $table->enum('extras', ['color', 'image'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_variation_structures');
    }
}
