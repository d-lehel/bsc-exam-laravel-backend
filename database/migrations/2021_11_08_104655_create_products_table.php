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
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('user_name');
            $table->string('product_name');
            $table->tinyInteger('is_active')->default(1);
            $table->string('description');
            $table->tinyInteger('amount');
            $table->timestamp('expiration')->nullable();
            $table->string('pickup_adress');

            $table->string('image_1')->default("");
            $table->string('image_2')->default("");
            $table->string('image_3')->default("");

            // index() -> gyorsabb sql lekerdezest eredmenyez
            $table->decimal('longitude',8,5)->index();
            $table->decimal('latitude',8,5)->index();
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
