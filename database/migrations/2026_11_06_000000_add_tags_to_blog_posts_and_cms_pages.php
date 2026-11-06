<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('tags')->nullable();
        });
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->json('tags')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', fn (Blueprint $table) => $table->dropColumn('tags'));
        Schema::table('blog_posts', fn (Blueprint $table) => $table->dropColumn('tags'));
    }
};
