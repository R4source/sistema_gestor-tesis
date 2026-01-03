<?php
// app/Models/Revision.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    protected $table = 'revisiones';

    protected $fillable = [
        'tesis_id',
        'profesor_id',
        'comentario',
        'estado'
    ];

    // Relaciones
    public function tesis()
    {
        return $this->belongsTo(Tesis::class);
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    // Scopes para estados
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeCorregidos($query)
    {
        return $query->where('estado', 'corregido');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }
}