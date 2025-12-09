<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // /**
        //  * PROVINCES TABLE
        //  */
        // Schema::create('master_provinces', function (Blueprint $table) {
        //     $table->id(); // prov_id
        //     $table->string('province_name');
        //     $table->timestamps();
        // });

        // /**
        //  * CITIES TABLE
        //  */
        // Schema::create('master_cities', function (Blueprint $table) {
        //     $table->id(); // city_id
        //     $table->string('city_name');
        //     $table->foreignId('province_id')->constrained('master_provinces')->cascadeOnDelete();
        //     $table->timestamps();
        // });

        // /**
        //  * DISTRICTS TABLE
        //  */
        // Schema::create('master_districts', function (Blueprint $table) {
        //     $table->id(); // dis_id
        //     $table->string('district_name');
        //     $table->foreignId('city_id')->constrained('master_cities')->cascadeOnDelete();
        //     $table->timestamps();
        // });

        // /**
        //  * SUBDISTRICTS TABLE
        //  */
        // Schema::create('master_subdistricts', function (Blueprint $table) {
        //     $table->id(); // subdis_id
        //     $table->string('subdistrict_name');
        //     $table->foreignId('district_id')->constrained('master_districts')->cascadeOnDelete();
        //     $table->timestamps();
        // });

        // /**
        //  * POSTAL CODES TABLE
        //  */
        // Schema::create('master_postal_codes', function (Blueprint $table) {
        //     $table->id(); // postal_id
        //     $table->string('postal_code', 10);
        //     $table->foreignId('subdistrict_id')->nullable()->constrained('master_subdistricts')->nullOnDelete();
        //     $table->foreignId('district_id')->nullable()->constrained('master_districts')->nullOnDelete();
        //     $table->foreignId('city_id')->nullable()->constrained('master_cities')->nullOnDelete();
        //     $table->foreignId('province_id')->nullable()->constrained('master_provinces')->nullOnDelete();
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('master_postal_codes');
        // Schema::dropIfExists('master_subdistricts');
        // Schema::dropIfExists('master_districts');
        // Schema::dropIfExists('master_cities');
        // Schema::dropIfExists('master_provinces');
    }
};
