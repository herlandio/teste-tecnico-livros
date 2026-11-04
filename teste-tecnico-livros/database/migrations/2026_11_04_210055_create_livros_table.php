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
        Schema::create('livro', function (Blueprint $table) {
            $table->increments('Codl');
            $table->string('Titulo', 40);
            $table->string('Editora', 40)->nullable();
            $table->integer('Edicao')->nullable();
            $table->string('AnoPublicacao', 4)->nullable();
            $table->decimal('Valor', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro');
    }
};
