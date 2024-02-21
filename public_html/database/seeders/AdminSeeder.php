<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ownerAdmin = Admin::query()->create([
            'name' => 'owner',
            'email' => 'owner@gmail.com',
            'phone'  => "+9618686868",
            'last_login_at' => now(),
            'password' => Hash::make("P@ssword12"),
            'is_active' => "1",
            'is_owner' => 1,
        ]);

        $superAdminRole = Role::where('name', 'super admin')->first();
        $ownerAdmin->assignRole($superAdminRole);

        $permissions = Permission::whereIn('name', ['permission1', 'permission2'])->get();
        $ownerAdmin->syncPermissions($permissions);
    }
}
