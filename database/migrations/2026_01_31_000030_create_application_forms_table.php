<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationFormsTable extends Migration
{
    public function up()
    {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('roll_no')->nullable();
            $table->integer('random_no')->nullable();
            $table->string('advertisement_title')->nullable();
            $table->string('post_serial_no')->nullable();
            $table->string('post_title')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('disability')->nullable();
            $table->integer('disability_percent')->nullable();
            $table->string('aadhaar_no')->nullable();
            $table->string('sub_caste')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('correspondence_address')->nullable();
            $table->string('domicile_district')->nullable();
            $table->longText('basic_details')->nullable();
            $table->longText('additional_details')->nullable();
            $table->integer('status')->nullable();
            $table->longText('remarks')->nullable();
            $table->integer('review')->nullable();
            $table->integer('submitted')->nullable();
            $table->integer('paid')->nullable();
            $table->string('hardcopy_received')->nullable();
            $table->string('scrutinized')->nullable();
            $table->longText('scrutiny_remark')->nullable();
            $table->string('eligible')->nullable();
            $table->longText('eligibility_remark')->nullable();
            $table->datetime('eligibility_updated_at')->nullable();
            $table->string('order_uid')->nullable();
            $table->datetime('admitcard_downloaded_at')->nullable();
            $table->datetime('interview_letter_downloaded_at')->nullable();
            $table->string('centre_name')->nullable();
            $table->string('centre_code')->nullable();
            $table->string('centre_address')->nullable();
            $table->string('centre_city')->nullable();
            $table->string('room_no')->nullable();
            $table->string('seat_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
