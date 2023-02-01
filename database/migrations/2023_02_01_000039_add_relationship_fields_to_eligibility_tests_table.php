<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEligibilityTestsTable extends Migration
{
    public function up()
    {
        Schema::table('eligibility_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_7962779')->references('id')->on('users');
        });
    }
}
