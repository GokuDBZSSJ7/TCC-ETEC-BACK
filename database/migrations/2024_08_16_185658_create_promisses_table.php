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
        Schema::create('promisses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('political_id')->constrained('users');
            $table->foreignId('party_id')->constrained('parties');
            $table->string('image_url')->nullable();
            $table->string('budget');
            $table->string('time');
            $table->integer('like')->nullable();
            $table->integer('deslike')->nullable();
            $table->enum('approvation', ['Aprovado', 'Desaprovado', 'Pendente'])->default('Pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promisses');
    }
};
