<?php

namespace App\Services;

use App\Models\Record;
use App\Models\SubProcess;
use App\Models\Type;

class RecordService
{
    // Generar codigo
    public static function generateCode($typeD, $subprocesD)
    {
        // Buscar Tipo y Sub Proceso
        $type = Type::findOrFail($typeD);
        $subProcess = SubProcess::findOrFail($subprocesD);

        // Obtener siglas
        $acronymType = $type->acronym;
        $acronymSubProcess = $subProcess->acronym;

        // Contar cuántos registros existen con la misma combinación
        $count = Record::where('type_id', $typeD)
            ->where('sub_process_id', $subprocesD)
            ->count();

        // Incrementar el consecutivo
        $consecutive = str_pad((int) $count + 1, 3, '0', STR_PAD_LEFT);

        // Asignación de valor al codigo
        $code = "{$acronymType}-{$acronymSubProcess}-{$consecutive}";

        return $code;
    }

}
