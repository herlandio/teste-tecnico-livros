<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assunto extends Model
{
    protected $table = 'assunto';

    protected $primaryKey = 'codAs';

    protected $fillable = [
        'Descricao',
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(
            Livro::class,
            'livro_assunto',
            'Assunto_codAs',
            'Livro_Codl'
        );
    }

    public function getRouteKeyName(): string
    {
        return 'codAs';
    }
}
