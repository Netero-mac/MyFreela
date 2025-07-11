<?php

namespace Neteromac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // <-- IMPORTANTE: Adicione esta linha

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        // Project está no mesmo namespace, então não precisa do 'use'
        // mas é uma boa prática adicionar para clareza.
        return $this->hasMany(Project::class);
    }
}