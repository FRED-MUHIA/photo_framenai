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
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('order_item_id')->nullable()->index();
            $table->string('guest_token')->nullable()->index();
            $table->string('original_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size_bytes');
            $table->unsignedInteger('width_px')->nullable();
            $table->unsignedInteger('height_px')->nullable();
            $table->decimal('estimated_dpi', 8, 2)->nullable();
            $table->text('validation_notes')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
