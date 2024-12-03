<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class ModelRoleUserSeeder extends Seeder
{
    public function run()
    {
        // Retrieve users
        $userOne = User::where('email', 'admin@example.com')->first();
        $userTwo = User::where('email', 'user@example.com')->first();

        // Retrieve roles
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        // Assign roles via `model_has_roles`
        DB::table('model_has_roles')->insert([
            [
                'role_id' => $adminRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $userOne->id,
            ],
            [
                'role_id' => $userRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $userTwo->id,
            ],
        ]);
    }
}
