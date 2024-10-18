<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginAttemptsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('blocked_until')->nullable();
            $table->integer('total_blocks')->default(0);
            $table->boolean('account_locked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'failed_login_attempts',
                'blocked_until',
                'total_blocks',
                'account_locked',
            ]);
        });
    }
}
