<?php

namespace App\Traits;

use App\Models\TesisLog;

trait LoggableTesis
{
    public function logChange(string $campo, $old = null, $new = null)
    {
        TesisLog::create([
            'tesis_id'   => $this->id,
            'user_id'    => auth()->id(),
            'accion'     => $campo,
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }
}