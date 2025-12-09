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
        Schema::create('subcats', function (Blueprint $table) {
            $table->id('subcat_id');
            $table->string('subcat_name');
            $table->string('subcat_slug')->unique();
            $table->longText('subcat_description')->nullable();
            $table->string('subcat_img')->nullable();
            $table->enum('subcat_status', ['active', 'inactive'])->default('inactive');
            $table->foreignId('cat_id')->constrained('cats', 'cat_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcats');
    }
};
