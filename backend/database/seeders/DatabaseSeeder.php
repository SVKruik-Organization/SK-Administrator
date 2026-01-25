<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\EventLog;
use App\Models\GuestUser;
use App\Models\Module;
use App\Models\ModuleItem;
use App\Models\ModuleItemGrant;
use App\Models\ScheduledTask;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserProfile;
use App\Models\UserProfileModule;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Creating modules...');
        $modules = Module::factory(10)->create();

        $this->command->info('Creating and attaching module items...');
        $moduleItems = collect();
        foreach ($modules as $module) {
            $moduleItems = $moduleItems->merge(
                ModuleItem::factory(10)->create(['module_id' => $module->id])
            );
        }

        $this->command->info('Creating roles...');
        $roles = UserRole::factory(10)->create();

        $this->command->info('Creating and attaching grants...');
        foreach ($roles as $role) {
            $moduleItems->random(min(5, $moduleItems->count()))->each(function (ModuleItem $moduleItem) use ($role) {
                ModuleItemGrant::factory()->create([
                    'role_id' => $role->id,
                    'module_item_id' => $moduleItem->id,
                ]);
            });
        }

        $this->command->info('Creating admin user...');
        $adminUser = User::factory()->create([
            'first_name' => 'Stefan',
            'last_name' => 'Kruik',
            'email' => 'me@stefankruik.nl',
            'password' => Hash::make('password'),
            'role_id' => UserRole::factory()->create([
                'name' => 'Admin',
                'description' => 'Admin role',
                'position' => 1,
            ])->id,
        ]);

        $this->command->info('Creating normal users...');
        $normalUsers = User::factory(10)->create([
            'role_id' => $roles->random()->id,
        ]);
        $users = $normalUsers->push($adminUser);

        $this->command->info('Creating guest users...');
        $guestUsers = GuestUser::factory(10)->create([
            'role_id' => $roles->random()->id,
        ]);

        $this->command->info('Creating and attaching user notifications for users...');
        foreach ($users as $user) {
            UserNotification::factory(10)->create([
                'object_type' => User::class,
                'object_id' => $user->id,
                'source' => 'system (factory)',
            ]);
        }
        $this->command->info('Creating and attaching user notifications for guest users...');
        foreach ($guestUsers as $guestUser) {
            UserNotification::factory(10)->create([
                'object_type' => GuestUser::class,
                'object_id' => $guestUser->id,
                'source' => 'system (factory)',
            ]);
        }

        $this->command->info('Creating and attaching user profiles for users...');
        $userProfiles = collect();
        foreach ($users as $user) {
            $userProfiles = $userProfiles->merge(UserProfile::factory(5)->create([
                'object_type' => User::class,
                'object_id' => $user->id,
            ]));
        }

        $this->command->info('Creating and attaching user profiles for guest users...');
        $guestUserProfiles = collect();
        foreach ($guestUsers as $guestUser) {
            $guestUserProfiles = $guestUserProfiles->merge(UserProfile::factory(5)->create([
                'object_type' => GuestUser::class,
                'object_id' => $guestUser->id,
            ]));
        }

        $this->command->info('Creating and attaching user profile modules for users...');
        $userProfileModules = collect();
        foreach ($userProfiles as $userProfile) {
            $userProfileModules = $userProfileModules->merge(UserProfileModule::factory(10)->create([
                'user_profile_id' => $userProfile->id,
            ]));
        }
        $this->command->info('Creating and attaching user profile modules for guest users...');
        $guestUserProfileModules = collect();
        foreach ($guestUserProfiles as $guestUserProfile) {
            $guestUserProfileModules = $guestUserProfileModules->merge(UserProfileModule::factory(10)->create([
                'user_profile_id' => $guestUserProfile->id,
            ]));
        }

        $this->command->info('Creating scheduled tasks...');
        ScheduledTask::factory(20)->create();

        $this->command->info('Creating event logs...');
        EventLog::factory(100)->create([
            'object_type' => User::class,
            'object_id' => $users->random()->id,
        ]);
    }
}
