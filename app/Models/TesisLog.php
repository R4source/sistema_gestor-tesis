<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TesisLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tesis_id',
        'user_id',
        'accion',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function tesis()
    {
        return $this->belongsTo(Tesis::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}