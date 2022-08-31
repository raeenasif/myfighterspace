<?php

use App\Domains\Auth\Models\User;
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
            $table->bigIncrements('id');
            $table->enum('type', [User::TYPE_ADMIN, User::TYPE_USER, User::TYPE_MEMBER, User::TYPE_FIGHTER, User::TYPE_TRAINER])->default(User::TYPE_MEMBER);
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('profileimage')->nullable();
            $table->string('payouts')->nullable();
            $table->integer('age')->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->nullable();
            $table->integer('weight')->nullable();
            $table->string('location')->nullable();
            $table->string('fightingstyle')->nullable();
            $table->string('bio')->nullable();
            $table->string('goals')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->unsignedTinyInteger('active')->default(1);
            $table->string('timezone')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('to_be_logged_out')->default(false);
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->integer('otp')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
