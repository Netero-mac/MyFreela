<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Invoice extends Model
{
    protected $fillable = [
        'project_id',
        'client_id',
        'user_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}