<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verlofaanvragens', function (Blueprint $table) {
            $table->id();
            $table->string('verlof_reden');
            $table->date('aanvraag_datum');
            $table->date('start_datum');
            $table->date('eind_datum');
            $table->integer("verlof_dagen")->default(25);
            $table->foreignId('verlof_soort')->constrained('statuses');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verlofaanvragens');
    }
};
