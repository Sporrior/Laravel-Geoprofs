<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->id(); // Primary key, which also acts as a foreign key to `users`
            $table->string('voornaam');
            $table->string('tussennaam')->nullable();
            $table->string('achternaam');
            $table->string('profielFoto')->nullable()->default('profile_pictures/default_profile_photo.png');
            $table->string('email')->unique();
            $table->string('telefoon');
            $table->integer('verlof_dagen')->default(25);
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('blocked_until')->nullable();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('team_id')->nullable()->constrained();
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_info');
    }
};