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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); 
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // 8 أرقام منهم 2 بعد العلامة العشرية
            $table->integer('stock_quantity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // دي بتعمل حقل deleted_at
            
            // Indexing لتسريع البحث بالفلاتر
            $table->index('category_id');
            $table->index('is_active');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
