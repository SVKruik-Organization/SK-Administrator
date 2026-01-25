<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleItem;
use Illuminate\Database\Seeder;

class ModuleItemSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating and attaching module items...');

        ModuleItem::create([
            'name' => [
                'en' => 'Dashboard',
                'nl' => 'Dashboard',
            ],
            'position' => 1,
            'icon' => 'fa-chart-pie',
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Users',
                'nl' => 'Gebruikers',
            ],
            'position' => 2,
            'icon' => 'fa-user-group',
        ]);

        $guildsModule = Module::where('name->en', 'Guilds')->first();
        ModuleItem::create([
            'name' => [
                'en' => 'Overview',
                'nl' => 'Overzicht',
            ],
            'position' => 1,
            'module_id' => $guildsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Economy',
                'nl' => 'Economie',
            ],
            'position' => 2,
            'module_id' => $guildsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Tier',
                'nl' => 'Niveau',
            ],
            'position' => 3,
            'module_id' => $guildsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Admins',
                'nl' => 'Beheerders',
            ],
            'position' => 4,
            'module_id' => $guildsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Blocked',
                'nl' => 'Geblokkeerd',
            ],
            'position' => 5,
            'module_id' => $guildsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Events',
                'nl' => 'Evenementen',
            ],
            'position' => 6,
            'module_id' => $guildsModule->id,
        ]);

        $operatorsModule = Module::where('name->en', 'Operators')->first();
        ModuleItem::create([
            'name' => [
                'en' => 'Teams',
                'nl' => 'Teams',
            ],
            'position' => 1,
            'module_id' => $operatorsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Owners',
                'nl' => 'Eigenaren',
            ],
            'position' => 2,
            'module_id' => $operatorsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Members',
                'nl' => 'Leden',
            ],
            'position' => 3,
            'module_id' => $operatorsModule->id,
        ]);

        $statusModule = Module::where('name->en', 'Status')->first();
        ModuleItem::create([
            'name' => [
                'en' => 'Sites',
                'nl' => 'Sites',
            ],
            'position' => 1,
            'module_id' => $statusModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Hardware',
                'nl' => 'Hardware',
            ],
            'position' => 2,
            'module_id' => $statusModule->id,
        ]);

        $activityModule = Module::where('name->en', 'Activity')->first();
        ModuleItem::create([
            'name' => [
                'en' => 'Logs',
                'nl' => 'Logboeken',
            ],
            'position' => 1,
            'module_id' => $activityModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Purchases',
                'nl' => 'Aankopen',
            ],
            'position' => 2,
            'module_id' => $activityModule->id,
        ]);

        $recordsModule = Module::where('name->en', 'Records')->first();
        ModuleItem::create([
            'name' => [
                'en' => 'Tickets',
                'nl' => 'Tickets',
            ],
            'position' => 1,
            'module_id' => $recordsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Questions',
                'nl' => 'Vragen',
            ],
            'position' => 2,
            'module_id' => $recordsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'User Reports',
                'nl' => 'Gebruikersrapporten',
            ],
            'position' => 3,
            'module_id' => $recordsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'User Warnings',
                'nl' => 'Gebruikerswaarschuwingen',
            ],
            'position' => 4,
            'module_id' => $recordsModule->id,
        ]);
        ModuleItem::create([
            'name' => [
                'en' => 'Bug Reports',
                'nl' => 'Bugrapporten',
            ],
            'position' => 5,
            'module_id' => $recordsModule->id,
        ]);
    }
}
