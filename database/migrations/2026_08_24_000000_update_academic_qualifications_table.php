<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('academic_qualifications', function (Blueprint $table) {
            $table->boolean('is_ugc_2009_compliant')->default(0)->after('title');
        });
    }

    public function down()
    {
        Schema::table('academic_qualifications', function (Blueprint $table) {
            $table->dropColumn('is_ugc_2009_compliant');
        });
    }
};
