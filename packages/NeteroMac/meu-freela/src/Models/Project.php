<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use NeteroMac\MeuFreela\Enums\ProjectStatus; 

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
        'status' => \App\Enums\ProjectStatus::class,
        'deadline' => 'date',
    ];

    /**
     * Define a relação de pertencimento a um Cliente.
     */
    public function client(): BelongsTo 
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Define a relação de pertencimento a um Usuário.
     */
    public function user(): BelongsTo 
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     *  Define a relação de um-para-um com a Fatura.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
