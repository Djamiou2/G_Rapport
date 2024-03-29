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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');

            $table->string('contact');
            $table->string('email');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default(bcrypt('12345678'));
            $table->rememberToken();

            // valeur par defaut pour les champs id_profil (3) (Utilisateur simple)
            $table->unsignedBigInteger('id_profil')->default(3);
            $table->foreign('id_profil')->
                references('id')->on('profils')->onDelete('cascade');

            $table->timestamps();

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_profil');
            $table->dropForeign('id_profil');
        });
        Schema::dropIfExists('users');
    }
};