<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasTable('category_product')) {
            return;
        }

        $products = DB::table('products')->select('id', 'category_id')->whereNotNull('category_id')->get();

        foreach ($products as $product) {
            DB::table('category_product')->updateOrInsert(
                ['category_id' => $product->category_id, 'product_id' => $product->id],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep pivot data on rollback.
    }
};
