<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('other_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fellowship_undergraduate');
            $table->string('fellowship_graduate')->nullable();
            $table->string('fellowship_postgraduate')->nullable();
            $table->string('phd_thesis_title')->nullable();
            $table->integer('research_phd_awarded')->nullable();
            $table->integer('research_phd_thesis')->nullable();
            $table->integer('research_phd_total_scholars')->nullable();
            $table->integer('research_mphil_awarded')->nullable();
            $table->integer('research_mphil_thesis')->nullable();
            $table->integer('research_mphil_total_scholars')->nullable();
            $table->integer('research_other_awarded')->nullable();
            $table->integer('research_other_thesis')->nullable();
            $table->integer('research_other_total_scholars')->nullable();
            $table->longText('eminent_scholar')->nullable();
            $table->longText('contribution_to_knowledge')->nullable();
            $table->longText('engaged_in_research')->nullable();
            $table->longText('industry_experience')->nullable();
            $table->string('current_pay_level')->nullable();
            $table->string('current_pay_range')->nullable();
            $table->string('current_basic_pay')->nullable();
            $table->string('current_pay_band')->nullable();
            $table->string('current_grade_pay')->nullable();
            $table->string('current_basic_pay_old')->nullable();
            $table->string('current_allowances')->nullable();
            $table->string('current_allowances_total')->nullable();
            $table->date('increment_date')->nullable();
            $table->longText('minimum_initial_pay')->nullable();
            $table->string('joining_time')->nullable();
            $table->integer('books_published')->nullable();
            $table->integer('books_accepted')->nullable();
            $table->integer('papers_published')->nullable();
            $table->integer('papers_accepted')->nullable();
            $table->integer('articles_published')->nullable();
            $table->integer('articles_accepted')->nullable();
            $table->integer('papers_read_published')->nullable();
            $table->integer('papers_read_accepted')->nullable();
            $table->string('eca_university_administration')->nullable();
            $table->string('eca_student')->nullable();
            $table->string('eca_residential_student')->nullable();
            $table->string('eca_cultural')->nullable();
            $table->longText('relevant_work')->nullable();
            $table->string('previous_applications')->nullable();
            $table->string('testimonial_1')->nullable();
            $table->string('testimonial_2')->nullable();
            $table->string('testimonial_3')->nullable();
            $table->longText('remark_essential_qualification')->nullable();
            $table->longText('remark_desirable_qualification')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
