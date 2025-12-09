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
        // Schema::create('master_globals', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('group');            // e.g. gender, religion, bank, status
        //     $table->string('code')->nullable(); // optional code like 'M', 'F', 'ISL', etc.
        //     $table->string('name');             // display name
        //     $table->text('description')->nullable();
        //     $table->integer('sort_order')->default(0);
        //     $table->boolean('is_active')->default(true);
        //     $table->json('meta')->nullable();   // for storing extra data (flexible)
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // Optional index for quick lookups
        // Schema::table('master_globals', function (Blueprint $table) {
        //     $table->index(['group', 'is_active']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('master_globals');
    }
};
