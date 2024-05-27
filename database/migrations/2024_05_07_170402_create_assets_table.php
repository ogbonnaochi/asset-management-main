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
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tagnumber');
            $table->string('assetcategory')->nullable();
            $table->string('assettype')->nullable();
            $table->string('assetname')->nullable();
            $table->string('assetdescription')->nullable();
            $table->string('landsize')->nullable();
            $table->string('arealocated')->nullable();
            $table->string('landmark')->nullable();
            $table->string('gpslocation')->nullable();
            $table->string('make')->nullable();
            $table->string('modelno')->nullable();
            $table->string('serialno')->nullable();
            $table->string('colour')->nullable();
            $table->string('year')->nullable();
            $table->string('engineno')->nullable();
            $table->string('chasisno')->nullable();
            $table->string('vehicleno')->nullable();
            $table->dateTime('datepurchase')->nullable();
            $table->decimal('purchasedamount', 10, 2)->nullable();
            $table->string('status')->nullable();
            $table->uuid('mdaid')->nullable();
            $table->uuid('assetlocation')->nullable();
            $table->string('locationdescription')->nullable();
            $table->uuid('custodian')->nullable();
            $table->string('pict1')->nullable();
            $table->string('pict2')->nullable();
            $table->string('pict3')->nullable();
            $table->string('pict4')->nullable();
            $table->string('video')->nullable();
            $table->string('submittedby')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
