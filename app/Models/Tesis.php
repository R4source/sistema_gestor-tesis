<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LoggableTesis;  

class Tesis extends Model
{
    use HasFactory, LoggableTesis; 
    use HasFactory;

    protected $table = 'tesis';

    protected $fillable = [
        'grupo_id',
        'titulo', 
        'resumen',
        'archivo', 
        'estado'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function revisiones()
    {
        return $this->hasMany(Revision::class);
    }

    // Scopes para estados
    public function scopeEnviadas($query)
    {
        return $query->where('estado', 'enviado');
    }

    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en revisión');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazado');
    }

    public function profesor()
    {
    return $this->hasOneThrough(
        User::class,      // destino: users
        Grupo::class,     // intermedio: grupos
        'id',             // fk en grupos
        'id',             // fk en users
        'grupo_id',       // local en tesis
        'profesor_id'     // local en grupos
    );
    }

    // Helper para obtener última revisión
    public function ultimaRevision()
    {
        return $this->revisiones()->latest()->first();
    }

    // Helper para verificar si tiene archivo
    public function tieneArchivo()
    {
        return !empty($this->archivo);
    }
     
     //Historial de cambios
    public function historial()
    {
    return $this->hasMany(TesisLog::class)->latest();
    }

}