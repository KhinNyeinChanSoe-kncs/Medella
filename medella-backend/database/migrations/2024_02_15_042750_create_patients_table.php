<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->boolean('inpatient');
            $table->longText('allergies_reactions')->nullable();
            $table->date('dob');
            $table->longText('surgery_histories')->nullable();
            $table->string('emergency_contact_number');
            $table->string('parent_name');
            $table->boolean('born_status')->nullable();
            $table->boolean('dead_status')->nullable();
            $table->date('born_date')->nullable();
            $table->date('dead_date')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('patients');
    }
}
