<?php

namespace NeteroMac\MeuFreela\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Enums\ProjectStatus; 

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

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'status' => ProjectStatus::class,
        ];
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