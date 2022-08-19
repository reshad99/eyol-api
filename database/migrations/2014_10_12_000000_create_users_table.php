<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profile_photo');
            $table->string('bio');
            $table->string('username')->unique();
            $table->date('dob');
            $table->string('gender');
            $table->integer('height');
            $table->string('interest');
            $table->string('zodiac');
            $table->string('alcohol');
            $table->string('phone')->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->boolean('hidden_profile')->default(false);
            $table->boolean('verified')->default(false);
            $table->string('followers')->default(0);
            $table->string('followings')->default(0);
            $table->string('posts')->default(0);
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
        Schema::dropIfExists('users');
    }
}
