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
        Schema::table('project_transactions', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('status')->constrained('payment_methods')->nullOnDelete();
            $table->decimal('tax_percentage', 5, 2)->default(10)->after('status');
            $table->string('payment_proof')->nullable()->after('tax_percentage');
            $table->decimal('payment_total', 15, 2)->nullable()->after('payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_transactions', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'tax_percentage', 'payment_proof', 'payment_total']);
        });
    }
};
