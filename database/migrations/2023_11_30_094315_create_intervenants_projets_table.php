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
        Schema::create('intervenants_projets', function (Blueprint $table) {
            $table->id();
            $table->integer('intervenant_id');
            $table->integer('projet_id');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intervenants_projets', function (Blueprint $table) {
            $table->dropForeign(['intervenant_id', 'projet_id']);
            $table->dropForeign(['intervenant_id', 'projet_id']);
        });

        Schema::dropIfExists('intervenants_projets');
    }
};
