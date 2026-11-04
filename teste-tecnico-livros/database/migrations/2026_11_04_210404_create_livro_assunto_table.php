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
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->unsignedInteger('Livro_Codl');
            $table->unsignedInteger('Assunto_codAs');

            $table->primary(['Livro_Codl', 'Assunto_codAs']);

            $table->foreign('Livro_Codl')
                ->references('Codl')
                ->on('livro')
                ->cascadeOnDelete();

            $table->foreign('Assunto_codAs')
                ->references('codAs')
                ->on('assunto')
                ->cascadeOnDelete();

            $table->index('Assunto_codAs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_assunto');
    }
};
