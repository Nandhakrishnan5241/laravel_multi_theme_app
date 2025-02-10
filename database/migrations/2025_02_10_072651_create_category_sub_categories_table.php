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
        Schema::create('category_sub_categories', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->string('slug'); 
            $table->text('description')->nullable(); 
            $table->string('image')->nullable(); 
            $table->unsignedBigInteger('parent_id')->nullable()->default(0); 
            $table->integer('level'); 
            $table->enum('level_name', ['category', 'subcategory']); 
            $table->foreign('parent_id')->references('id')->on('category_sub_categories')->onDelete('cascade'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_sub_categories');
    }
};
