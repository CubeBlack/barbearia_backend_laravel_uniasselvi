<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'Clientes';
    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'endereco'
    ];

    public function agendamentos(){
        return $tthi->hasMany(Agendamento::class);
    }
}
