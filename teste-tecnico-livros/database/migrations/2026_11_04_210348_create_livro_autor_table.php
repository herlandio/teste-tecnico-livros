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
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->unsignedInteger('Livro_Codl');
            $table->unsignedInteger('Autor_CodAu');

            $table->primary(['Livro_Codl', 'Autor_CodAu']);

            $table->foreign('Livro_Codl')
                ->references('Codl')
                ->on('livro')
                ->cascadeOnDelete();

            $table->foreign('Autor_CodAu')
                ->references('CodAu')
                ->on('autor')
                ->cascadeOnDelete();

            $table->index('Autor_CodAu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};
