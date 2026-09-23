<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
    ];

    protected $hidden = [
        'senha',
    ];

    public function setSenhaAttribute($value): void
    {
        $this->attributes['senha'] = password_get_info($value)['algo'] === null
            ? password_hash($value, PASSWORD_DEFAULT)
            : $value;
    }
}
