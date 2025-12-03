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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_transaction_id')->nullable();
            $table->string('po_number')->unique();
            $table->date('order_date')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);     
            $table->unsignedBigInteger('status')->nullable();

            $table->foreign('status')
                ->references('id')
                ->on('master_globals')
                ->onUpdate('cascade');
            $table->date('payment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_transaction_id')->references('id')->on('project_transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
