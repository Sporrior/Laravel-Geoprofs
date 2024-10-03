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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->unique(); // Renamed 'Group' to 'group_name'
            $table->timestamps();
        });

        // Insert predefined teams
        DB::table('teams')->insert([ // Fixed to insert into 'teams'
            ['group_name' => 'GeoICT'],
            ['group_name' => 'GeoDECY'],
            ['group_name' => 'HRM'],
            ['group_name' => 'Finances'],
            ['group_name' => 'ICT'],
            ['group_name' => 'OM'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
