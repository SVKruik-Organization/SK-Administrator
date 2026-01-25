<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating modules...');
        Module::create([
            'name' => [
                'en' => 'Activity',
                'nl' => 'Activiteit',
            ],
            'icon' => 'fa-wave-pulse',
        ]);
        Module::create([
            'name' => [
                'en' => 'Guilds',
                'nl' => 'Guilds',
            ],
            'icon' => 'fa-users-gear',
        ]);
        Module::create([
            'name' => [
                'en' => 'Operators',
                'nl' => 'Operators',
            ],
            'icon' => 'fa-user-group-crown',
        ]);
        Module::create([
            'name' => [
                'en' => 'Preferences',
                'nl' => 'Voorkeuren',
            ],
            'icon' => 'fa-gear',
        ]);
        Module::create([
            'name' => [
                'en' => 'Records',
                'nl' => 'Documenten',
            ],
            'icon' => 'fa-folder-open',
        ]);
        Module::create([
            'name' => [
                'en' => 'Status',
                'nl' => 'Status',
            ],
            'icon' => 'fa-satellite-dish',
        ]);
    }
}
