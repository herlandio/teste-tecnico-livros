<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement(<<<'SQL'
                CREATE VIEW vw_livros_por_autor AS
                SELECT
                    a."CodAu" AS autor_id,
                    a."Nome" AS autor_nome,
                    l."Codl" AS livro_id,
                    l."Titulo" AS livro_titulo,
                    l."Editora" AS editora,
                    l."Edicao" AS edicao,
                    l."AnoPublicacao" AS ano_publicacao,
                    l."Valor" AS valor,
                    COALESCE(
                        GROUP_CONCAT(s."Descricao", ', '),
                        ''
                    ) AS assuntos
                FROM autor a
                LEFT JOIN livro_autor la
                    ON la."Autor_CodAu" = a."CodAu"
                LEFT JOIN livro l
                    ON l."Codl" = la."Livro_Codl"
                LEFT JOIN livro_assunto ls
                    ON ls."Livro_Codl" = l."Codl"
                LEFT JOIN assunto s
                    ON s."codAs" = ls."Assunto_codAs"
                GROUP BY
                    a."CodAu",
                    a."Nome",
                    l."Codl",
                    l."Titulo",
                    l."Editora",
                    l."Edicao",
                    l."AnoPublicacao",
                    l."Valor";
            SQL);

            return;
        }

        DB::statement(<<<'SQL'
            CREATE VIEW vw_livros_por_autor AS
            SELECT
                a."CodAu" AS autor_id,
                a."Nome" AS autor_nome,
                l."Codl" AS livro_id,
                l."Titulo" AS livro_titulo,
                l."Editora" AS editora,
                l."Edicao" AS edicao,
                l."AnoPublicacao" AS ano_publicacao,
                l."Valor" AS valor,
                COALESCE(
                    STRING_AGG(s."Descricao", ', ' ORDER BY s."Descricao"),
                    ''
                ) AS assuntos
            FROM autor a
            LEFT JOIN livro_autor la
                ON la."Autor_CodAu" = a."CodAu"
            LEFT JOIN livro l
                ON l."Codl" = la."Livro_Codl"
            LEFT JOIN livro_assunto ls
                ON ls."Livro_Codl" = l."Codl"
            LEFT JOIN assunto s
                ON s."codAs" = ls."Assunto_codAs"
            GROUP BY
                a."CodAu",
                a."Nome",
                l."Codl",
                l."Titulo",
                l."Editora",
                l."Edicao",
                l."AnoPublicacao",
                l."Valor";
        SQL);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_livros_por_autor');
    }
};
