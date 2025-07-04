<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->date('birth_date');
            $table->date('start_date');
            $table->date('delivery_date');
            $table->string('prescriber');
            $table->string('phone');
            $table->json('care_types');
            $table->json('equipments');
            $table->json('sonde_followup');
            $table->json('stoma_followup');
            $table->json('wound_followup');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};
