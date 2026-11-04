<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    protected $table = 'livro';

    protected $primaryKey = 'Codl';

    protected $fillable = [
        'Titulo',
        'Editora',
        'Edicao',
        'AnoPublicacao',
        'Valor',
    ];

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(
            Autor::class,
            'livro_autor',
            'Livro_Codl',
            'Autor_CodAu'
        );
    }

    public function assuntos(): BelongsToMany
    {
        return $this->belongsToMany(
            Assunto::class,
            'livro_assunto',
            'Livro_Codl',
            'Assunto_codAs'
        );
    }
}
