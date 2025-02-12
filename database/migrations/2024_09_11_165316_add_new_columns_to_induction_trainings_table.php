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
        Schema::table('induction_trainings', function (Blueprint $table) {
            $table->longText('on_the_job_comment')->nullable();
            $table->longText('on_the_job_attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('induction_trainings', function (Blueprint $table) {
            //
        });
    }
};
