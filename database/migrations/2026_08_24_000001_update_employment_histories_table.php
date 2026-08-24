<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employment_histories', function (Blueprint $table) {
            $table->boolean('is_permanent')->default(1)->after('designation');
            $table->string('pay_level')->nullable()->after('salary');
            $table->integer('duration_days')->default(0)->after('end_date');
        });
    }

    public function down()
    {
        Schema::table('employment_histories', function (Blueprint $table) {
            $table->dropColumn(['is_permanent', 'pay_level', 'duration_days']);
        });
    }
};
