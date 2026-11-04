<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    protected $table = 'autor';

    protected $primaryKey = 'CodAu';

    protected $fillable = [
        'Nome',
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(
            Livro::class,
            'livro_autor',
            'Autor_CodAu',
            'Livro_Codl'
        );
    }

    public function getRouteKeyName(): string
    {
        return 'CodAu';
    }
}
