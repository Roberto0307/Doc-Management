<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* Type::factory(5)->create(); */

        Type::factory()->create([
            'title' => 'Documento',
            'acronym' => 'D',
        ]);
        Type::factory()->create([
            'title' => 'Instructivo',
            'acronym' => 'I',
        ]);
        Type::factory()->create([
            'title' => 'Politica',
            'acronym' => 'P',
        ]);
        Type::factory()->create([
            'title' => 'Matriz',
            'acronym' => 'M',
        ]);
        Type::factory()->create([
            'title' => 'Formato',
            'acronym' => 'F',
        ]);
    }
}
