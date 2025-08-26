<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Invoice extends Model
{
    use HasFactory;

    /**
     * Converte atributos para tipos de dados nativos.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_amount' => 'decimal:2', 
    ];

 
    protected $fillable = [
        'user_id',
        'project_id',
        'client_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
