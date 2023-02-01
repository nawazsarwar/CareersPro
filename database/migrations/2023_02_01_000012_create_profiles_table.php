<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile')->nullable();
            $table->datetime('mobile_verified_at')->nullable();
            $table->string('alternate_mobile')->nullable();
            $table->string('pwd')->nullable();
            $table->integer('disability_percent')->nullable();
            $table->string('aadhaar_no')->nullable();
            $table->string('sub_caste')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('identity_marks')->nullable();
            $table->longText('remarks')->nullable();
            $table->integer('verified');
            $table->integer('locked');
            $table->string('conviction')->nullable();
            $table->string('conviction_reason')->nullable();
            $table->string('debarred')->nullable();
            $table->string('debarred_reason')->nullable();
            $table->string('vigilance')->nullable();
            $table->string('vigilance_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
