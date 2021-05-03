<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->from(1000);
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->float('unit_measure');
            $table->float('avail_unit_measure');
            $table->float('price');
            $table->string('part')->nullable();
            $table->boolean('status')->default(true);
            $table->longText('description')->nullable();
            $table->longText('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
