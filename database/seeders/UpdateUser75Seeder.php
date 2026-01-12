<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UpdateUser75Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Ensure the specific permission exists
        $permissions = [
            'can edit sectors',
            'can edit units',
        ];

        $userId = 75;
        $user = User::find($userId);

        foreach ($permissions as $permissionName) {

            $permission = Permission::firstOrCreate(['name' => $permissionName]);

            if ($user) {
                // Assign permission directly to the user if they don't have it
                if (!$user->hasPermissionTo($permissionName)) {
                    $user->givePermissionTo($permission);
                    $this->command->info("Permission '{$permissionName}' successfully assigned to user {$userId}.");
                } else {
                    $this->command->info("User {$userId} already has '{$permissionName}' permission.");
                }
            } else {
                $this->command->error("User with ID {$userId} not found.");
            }
        }


        // Clear cache again to ensure changes take effect immediately
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
