<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('follower_user', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'follower_id')->constrained('users');
            $table->foreignIdFor(User::class, 'following_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
