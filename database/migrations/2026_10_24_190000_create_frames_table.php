<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190);
            $table->string('slug', 190)->unique();
            $table->string('sku', 90)->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('material', 120)->nullable();
            $table->string('asset_type', 24)->default('overlay');
            $table->string('thumbnail_path')->nullable();
            $table->string('overlay_path')->nullable();
            $table->string('landscape_overlay_path')->nullable();
            $table->string('texture_path')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('min_width_cm', 5, 2)->default(2);
            $table->decimal('max_width_cm', 5, 2)->default(12);
            $table->decimal('width_price_per_cm', 12, 2)->default(0);
            $table->json('orientations')->nullable();
            $table->json('sizes')->nullable();
            $table->json('accessories')->nullable();
            $table->unsignedInteger('inventory')->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frames');
    }
};
