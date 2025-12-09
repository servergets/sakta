<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin Role
        $superAdmin = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Full access to all modules and features',
            'is_active' => true,
        ]);

        // Assign all permissions to Super Admin
        $allPermissions = Permission::all();
        $superAdmin->permissions()->sync($allPermissions->pluck('id'));

        // Create Manager Role
        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Access to most modules except user and role management',
            'is_active' => true,
        ]);

        $managerPermissions = Permission::whereNotIn('module', ['User Management', 'Role Management'])->get();
        $manager->permissions()->sync($managerPermissions->pluck('id'));

        // Create Staff Role
        $staff = Role::create([
            'name' => 'staff',
            'display_name' => 'Staff',
            'description' => 'Limited access to daily operations',
            'is_active' => true,
        ]);

        $staffPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'project.view',
            'buyer.view', 'buyer.create', 'buyer.edit',
            'product.view',
            'sale.view', 'sale.create', 'sale.edit',
            'transaction.view', 'transaction.create',
            'client.view',
        ])->get();
        $staff->permissions()->sync($staffPermissions->pluck('id'));

        // Create Accountant Role
        $accountant = Role::create([
            'name' => 'accountant',
            'display_name' => 'Accountant',
            'description' => 'Access to financial modules and reports',
            'is_active' => true,
        ]);

        $accountantPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'transaction.view', 'transaction.create', 'transaction.edit',
            'sale.view',
            'buyer.view',
            'project.view',
            'estimation.view', 'estimation.create', 'estimation.edit',
        ])->get();
        $accountant->permissions()->sync($accountantPermissions->pluck('id'));
    }
}