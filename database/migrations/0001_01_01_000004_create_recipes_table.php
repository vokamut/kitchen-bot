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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('category')->index();
            $table->string('title')->collation('utf8mb4_unicode_ci')->charset('utf8mb4');
            $table->string('link', 2048)->nullable();
            $table->string('text', 4096)->nullable()->collation('utf8mb4_unicode_ci')->charset('utf8mb4');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
