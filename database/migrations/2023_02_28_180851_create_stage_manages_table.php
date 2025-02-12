<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_manages', function (Blueprint $table) {
            $table->id();
            $table->string('document_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('role')->nullable();
            $table->string('stage')->default(0)->nullable();
            $table->string('comment')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('stage_manages');
    }
};
