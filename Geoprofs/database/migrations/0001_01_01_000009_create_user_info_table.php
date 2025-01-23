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
            $table->id();
            $table->string('voornaam')->default('');
            $table->string('tussennaam')->nullable();
            $table->string('achternaam')->default('');
            $table->string('profielFoto')->nullable()->default('profile_pictures/default_profile_photo.png');
            $table->string('email')->unique()->default('');
            $table->string('telefoon')->default('');
            $table->integer('verlof_dagen')->default(25);
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('blocked_until')->nullable();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('team_id')->nullable()->constrained();
            $table->timestamps();
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