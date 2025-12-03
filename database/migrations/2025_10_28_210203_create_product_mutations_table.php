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
        Schema::create('product_mutations', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke product
            $table->foreignId('product_id')
                ->constrained('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            // Relasi ke master_globals untuk jenis mutasi
            $table->unsignedBigInteger('mutation_type_id');
            $table->foreign('mutation_type_id')
                ->references('id')
                ->on('master_globals')
                ->onUpdate('cascade');

            $table->dateTime('mutation_date')->index();
            $table->string('reference_code', 100)->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('qty_in')->default(0);
            $table->unsignedBigInteger('qty_out')->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_value', 20, 2)->default(0);
            $table->unsignedBigInteger('current_stock')->default(0);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Indexing untuk optimasi
            $table->index(
                ['product_id', 'mutation_type_id', 'mutation_date'],
                'pm_prod_type_date_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_mutations');
    }
};
