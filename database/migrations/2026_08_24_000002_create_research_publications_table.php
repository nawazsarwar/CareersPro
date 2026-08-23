<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('research_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Journal, Book, Chapter, etc.
            $table->string('title');
            $table->string('publisher_journal');
            $table->string('issn_isbn')->nullable();

            // API Specific Fields
            $table->boolean('is_peer_reviewed')->default(0);
            $table->boolean('is_ugc_care_listed')->default(0);
            $table->float('impact_factor', 5, 2)->nullable();
            $table->string('authorship_position'); // First, Corresponding, Co-Author
            $table->integer('number_of_coauthors')->default(0);
            $table->string('link_doi')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('research_publications');
    }
};
