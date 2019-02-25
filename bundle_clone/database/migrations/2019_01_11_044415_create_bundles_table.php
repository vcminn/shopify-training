<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('bundle_style');
            $table->integer('image_style');
            $table->integer('active');
            $table->string('widget_title');
            $table->string('internal_name');
            $table->string('description');
            $table->string('image');
            $table->float('base_total_price');
            $table->float('discount_price');
            $table->float('discount');
            $table->boolean('test');
            $table->timestamps();
        });

        Schema::create('bundle_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('bundle_id');
            $table->string('variant_id');
            $table->float('price');
            $table->string('image');
            $table->integer('quantity');
            $table->integer('stock');
            $table->timestamps();
        });

        Schema::create('bundle_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('bundle_id');
            $table->integer('sales');
            $table->integer('visitors');
            $table->integer('added_to_cart');
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
        Schema::dropIfExists('bundles');
        Schema::dropIfExists('bundle_products');
        Schema::dropIfExists('bundle_responses');
    }
}
