<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
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
            $table->boolean('inpatient_status')->default(0);
            $table->float('weight');
            $table->string('blood_pressure');
            $table->string('blood_glucose');
            $table->string('heart_rate');
            $table->string('temperature');
            $table->string('repository_rate');
            $table->string('oxygen_level');
            $table->string('vital_sign');
            $table->string('files_records');
            $table->longText('prescription');
            $table->string('disease');
            $table->longText('clinical_notes');
            $table->integer('patient_id');
            $table->integer('doctor_id');
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
}
