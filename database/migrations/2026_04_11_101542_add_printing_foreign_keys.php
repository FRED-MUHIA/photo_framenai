<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        Schema::table('product_option_groups', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('product_option_values', function (Blueprint $table) {
            $table->foreign('product_option_group_id')->references('id')->on('product_option_groups')->cascadeOnDelete();
        });

        Schema::table('product_quantity_prices', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('order_item_id')->references('id')->on('order_items')->nullOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('artwork_id')->references('id')->on('artworks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['artwork_id']);
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['order_item_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('product_quantity_prices', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_option_values', function (Blueprint $table) {
            $table->dropForeign(['product_option_group_id']);
        });

        Schema::table('product_option_groups', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    }
};
