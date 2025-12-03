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
        Schema::create('estimations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->integer('estimation_long')->nullable();
            $table->foreignId('client_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->date('estimation_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'expired'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimations');
    }
};
