<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel untuk Navigation Groups
        Schema::create('navigation_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->string('icon')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('collapsible')->default(true);
            
            // 👇 Single permissions check (Spatie)
            $table->string('permissions')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'sort']);
        });

        // Tabel untuk Navigation Items
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('navigation_group_id')
                ->nullable()
                ->constrained('navigation_groups')
                ->cascadeOnDelete();
            
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('navigation_items')
                ->cascadeOnDelete();
            
            $table->string('name');
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('url');
            $table->string('resource')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_group')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            $table->boolean('visible')->default(false);
            $table->text('roles')->nullable();
            
            // 👇 Single permissions check (Spatie) - MAIN METHOD
            $table->string('permissions')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(
                ['navigation_group_id', 'is_active', 'is_visible', 'sort'], 
                'nav_items_group_active_isvisible_sort_idx' // ← Nama pendek custom
            );
            // Index untuk permissions
            $table->index('permissions', 'nav_items_permissions_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('navigation_groups');
    }
};