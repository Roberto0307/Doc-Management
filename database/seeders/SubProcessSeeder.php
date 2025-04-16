<?php

namespace Database\Seeders;

use App\Models\SubProcess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* SubProcess::factory(10)->create(); */

        /* Cadena de suministros */
        SubProcess::factory()->create([
            'title' => 'Comecio exterior',
            'acronym' => 'CE',
            'process_id' => 1,
        ]);
        SubProcess::factory()->create([
            'title' => 'Servicio al cliente',
            'acronym' => 'SAC',
            'process_id' => 1,
        ]);
        /* Calidad */
        SubProcess::factory()->create([
            'title' => 'Aprobación de producto terminado',
            'acronym' => 'APT',
            'process_id' => 2,
        ]);
        SubProcess::factory()->create([
            'title' => 'Producto no conforme',
            'acronym' => 'PNC',
            'process_id' => 2,
        ]);
        /* Gestión financiera */
        SubProcess::factory()->create([
            'title' => 'Contabilidad',
            'acronym' => 'CT',
            'process_id' => 3,
        ]);
        SubProcess::factory()->create([
            'title' => 'Tesorería',
            'acronym' => 'TR',
            'process_id' => 3,
        ]);
        /* Gestión humana */
        SubProcess::factory()->create([
            'title' => 'Gestión de desempeño',
            'acronym' => 'GD',
            'process_id' => 4,
        ]);
        SubProcess::factory()->create([
            'title' => 'Servicios generales',
            'acronym' => 'SG',
            'process_id' => 4,
        ]);
        /* Investigación y desarrollo */
        SubProcess::factory()->create([
            'title' => 'Diseño de productos',
            'acronym' => 'DP',
            'process_id' => 5,
        ]);
        SubProcess::factory()->create([
            'title' => 'Mantenimiento de portafolio',
            'acronym' => 'MP',
            'process_id' => 5,
        ]);
    }
}
