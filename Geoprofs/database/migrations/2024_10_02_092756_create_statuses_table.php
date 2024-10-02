<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status')->unique();
            $table->timestamps();
        });

        // Insert predefined statuses
        DB::table('statuses')->insert([ // Fixed table name here
            ['status' => 'Ziekmelden'],
            ['status' => 'Vakantie'],
            ['status' => 'Personelijk'],
            ['status' => 'Verlof'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
