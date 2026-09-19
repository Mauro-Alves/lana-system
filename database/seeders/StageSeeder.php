<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        Stage::create(['name' => 'Pendentes', 'color' => '#fef3c7', 'order' => 1]);
        Stage::create(['name' => 'Em Andamento', 'color' => '#dbeafe', 'order' => 2]);
        Stage::create(['name' => 'Concluídas', 'color' => '#d1fae5', 'order' => 3, 'is_final' => true]);
    }
}