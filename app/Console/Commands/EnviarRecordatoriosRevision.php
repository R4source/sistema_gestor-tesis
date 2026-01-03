<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tesis;
use App\Notifications\RecordatorioRevisionNotification;
use Carbon\Carbon;

class EnviarRecordatoriosRevision extends Command
{
    protected $signature = 'recordatorios:revision';
    protected $description = 'Enviar recordatorios de tesis pendientes de revisión';

    public function handle()
    {
        $tesisPendientes = Tesis::where('estado', 'enviado')
            ->where('created_at', '<=', Carbon::now()->subDays(3))
            ->get();

        foreach ($tesisPendientes as $tesis) {
            $diasPendiente = $tesis->created_at->diffInDays(Carbon::now());
            $profesor = $tesis->grupo->profesor;
            
            $profesor->notify(new RecordatorioRevisionNotification($tesis, $diasPendiente));
            
            $this->info("Recordatorio enviado a {$profesor->name} para la tesis: {$tesis->titulo}");
        }

        $this->info("Se enviaron {$tesisPendientes->count()} recordatorios.");
    }
}