<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NeteroMac\MeuFreela\Enums\ProjectStatus; // 👈 1. Enum agora pertence ao pacote

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'title',
        'description',
        'value',
        'status',
        'deadline',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => ProjectStatus::class,
        'deadline' => 'date',
    ];

    /**
     * Define a relação de pertencimento a um Cliente.
     */
    public function client(): BelongsTo // 👈 3. Adicione o type hint
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Define a relação de pertencimento a um Usuário.
     */
    public function user(): BelongsTo // 👈 3. Adicione o type hint
    {
        // 👇 2. Forma dinâmica de obter o model de usuário
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}