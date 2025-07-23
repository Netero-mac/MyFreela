<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Invoice extends Model
{
    use HasFactory;

    // Adicione os campos que podem ser preenchidos em massa
    protected $fillable = [
        'user_id',
        'project_id',
        'client_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'due_date',
        'status'
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
