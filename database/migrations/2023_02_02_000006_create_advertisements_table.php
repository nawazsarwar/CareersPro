<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->date('dated')->nullable();
            $table->string('advertisement_url')->nullable();
            $table->decimal('default_fee', 15, 2)->nullable();
            $table->datetime('default_open_date')->nullable();
            $table->datetime('default_end_date')->nullable();
            $table->datetime('default_payment_end_date')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->string('status');
            $table->longText('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
