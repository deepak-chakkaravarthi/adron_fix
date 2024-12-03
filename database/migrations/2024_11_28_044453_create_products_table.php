<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable(); // To store the image path
            $table->decimal('final_price', 10, 2)->nullable(); // Final price after profit margin
            $table->enum('profit_margin_type', ['percentage', 'amount'])->nullable(); // Whether the profit margin is a percentage or amount
            $table->decimal('profit_margin_value', 10, 2)->nullable(); // Value of profit margin
            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->primary(['category_id', 'product_id']);
        });

        Schema::create('product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained();
            $table->string('tag');
            $table->primary(['product_id', 'tag']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_info');
            $table->timestamps();
        });

        Schema::create('product_supplier', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_supplier');
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('products');
    }
};

