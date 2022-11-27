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
        Schema::create('tm_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('emp_id', 10)->unique();
            $table->string('fullname');
            $table->enum('gender', ['Laki-Laki', 'Perempuan']);
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('address');
            $table->string('phone', 15)->unique;
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('tm_user');
    }
};
