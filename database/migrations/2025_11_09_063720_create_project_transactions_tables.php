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
        Schema::create('project_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->date('start_date')->nullable();
            $table->string('estimation')->nullable(); // misal: '4 Bulan'
            $table->decimal('total_amount', 20, 2)->default(0);
            $table->decimal('purchase_percentage', 5, 2)->nullable();  
            $table->unsignedBigInteger('status')->default(47);



            $table->foreign('status')
                ->references('id')
                ->on('master_globals')
                ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_transactions');
    }
};
