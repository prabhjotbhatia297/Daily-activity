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
        Schema::table('print_histories', function (Blueprint $table) {
            $table->text('document_printed_copies')->nullable();
            $table->string('issuance_to')->nullable();
            $table->text('issued_copies')->nullable();
            $table->text('issued_reason')->nullable();
            $table->text('department')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('print_histories', function (Blueprint $table) {
            //
        });
    }
};
