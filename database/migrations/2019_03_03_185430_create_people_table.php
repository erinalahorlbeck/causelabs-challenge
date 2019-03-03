<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // As per the "Coding Challenge" intructions:
        //   Use this secret phrase somewhere in your code's comments
        //   resource violation
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('email_addresses');
            $table->json('original_data');
            $table->ipAddress('ip_address');
            // 'created_at' is obviously equivalent to 'posted_at'
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
        Schema::dropIfExists('people');
    }
}
