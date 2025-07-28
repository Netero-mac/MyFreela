<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NeteroMac\MeuFreela\Database\Factories\ClientFactory;
use NeteroMac\MeuFreela\Models\Project; 

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 1. Apenas $fillable é necessário, $guarded foi removido.
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
    ];

    /**
     * Define a relação de pertencimento a um Usuário.
     */
    public function user(): BelongsTo
    {
        // Usando a classe User diretamente para mais clareza
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Define a relação de um para muitos com os Projetos.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    // 3. Adicionado o tipo de retorno para a Factory específica
    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }
}