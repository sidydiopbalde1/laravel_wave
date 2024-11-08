<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->float('montant');
            $table->string('status');
            $table->dateTime('date');
            $table->float('frais');
            $table->string('type');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
     
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter les colonnes supprimées (pour pouvoir annuler la migration si nécessaire)
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
         
        });
    }

};
