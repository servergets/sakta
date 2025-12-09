<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('buyers', function (Blueprint $table) {
        //     $table->id();

        //     // Profile & Personal Information
        //     $table->string('profile_photo')->nullable();
        //     $table->string('buyer_name');
        //     $table->string('citizenship');
        //     $table->date('birth_date');
        //     $table->unsignedBigInteger('gender_id'); // from master_genders
        //     $table->unsignedBigInteger('religion_id')->nullable(); // from master_religions
        //     $table->string('id_number')->unique(); // KTP
        //     $table->string('tax_number')->nullable(); // NPWP
        //     $table->string('phone')->nullable();
        //     $table->string('company_name');
        //     $table->string('email')->unique();

        //     // Bank Information
        //     $table->string('account_holder_name');
        //     $table->unsignedBigInteger('bank_id'); // from master_banks
        //     $table->string('account_number');

        //     // Home Address
        //     $table->text('home_address');
        //     $table->unsignedBigInteger('home_country_id');   // from master_countries
        //     $table->unsignedBigInteger('home_province_id');  // from master_provinces
        //     $table->unsignedBigInteger('home_city_id');      // from master_cities
        //     $table->unsignedBigInteger('home_district_id');
        //     $table->unsignedBigInteger('home_subdistrict_id');
        //     $table->string('home_postal_code');

        //     // Office Address
        //     $table->text('office_address')->nullable();
        //     $table->unsignedBigInteger('office_country_id')->nullable();
        //     $table->unsignedBigInteger('office_province_id')->nullable();
        //     $table->unsignedBigInteger('office_city_id')->nullable();
        //     $table->unsignedBigInteger('office_district_id')->nullable();
        //     $table->unsignedBigInteger('office_subdistrict_id')->nullable();
        //     $table->string('office_postal_code')->nullable();

        //     // Supporting Documents
        //     $table->string('id_card_file')->nullable();   // KTP upload
        //     $table->string('tax_card_file')->nullable();  // NPWP upload

        //     $table->timestamps();
        //     $table->softDeletes();


        //     $table->foreign('gender_id')->references('id')->on('master_globals');
        //     $table->foreign('religion_id')->references('id')->on('master_globals');
        //     $table->foreign('home_city_id')->references('id')->on('master_cities');
        //     $table->foreign('home_province_id')->references('id')->on('master_provinces');
        //     $table->foreign('office_city_id')->references('id')->on('master_cities');
        //     $table->foreign('office_province_id')->references('id')->on('master_provinces');

        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('buyers');
    }
};
