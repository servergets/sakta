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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('project_photo')->nullable();
            // $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('product_id');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('max_buyer');
            $table->integer('min_purchase');
            $table->integer('margin_sakta');
            $table->integer('margin_sales');
            $table->integer('margin_buyer');
            $table->integer('status')->default(51);
            $table->decimal('budget', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
