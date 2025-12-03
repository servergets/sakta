<?php
// database/seeders/PermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'Dashboard'],

            // Auth & User Management
            ['name' => 'user.view', 'display_name' => 'View Users', 'module' => 'User Management'],
            ['name' => 'user.create', 'display_name' => 'Create Users', 'module' => 'User Management'],
            ['name' => 'user.edit', 'display_name' => 'Edit Users', 'module' => 'User Management'],
            ['name' => 'user.delete', 'display_name' => 'Delete Users', 'module' => 'User Management'],

            // Role Management
            ['name' => 'role.view', 'display_name' => 'View Roles', 'module' => 'Role Management'],
            ['name' => 'role.create', 'display_name' => 'Create Roles', 'module' => 'Role Management'],
            ['name' => 'role.edit', 'display_name' => 'Edit Roles', 'module' => 'Role Management'],
            ['name' => 'role.delete', 'display_name' => 'Delete Roles', 'module' => 'Role Management'],

            // Project Management
            ['name' => 'project.view', 'display_name' => 'View Projects', 'module' => 'Project Management'],
            ['name' => 'project.create', 'display_name' => 'Create Projects', 'module' => 'Project Management'],
            ['name' => 'project.edit', 'display_name' => 'Edit Projects', 'module' => 'Project Management'],
            ['name' => 'project.delete', 'display_name' => 'Delete Projects', 'module' => 'Project Management'],

            // Transaction Management
            ['name' => 'transaction.view', 'display_name' => 'View Transactions', 'module' => 'Transaction Management'],
            ['name' => 'transaction.create', 'display_name' => 'Create Transactions', 'module' => 'Transaction Management'],
            ['name' => 'transaction.edit', 'display_name' => 'Edit Transactions', 'module' => 'Transaction Management'],
            ['name' => 'transaction.delete', 'display_name' => 'Delete Transactions', 'module' => 'Transaction Management'],

            // Buyer Management
            ['name' => 'buyer.view', 'display_name' => 'View Buyers', 'module' => 'Buyer Management'],
            ['name' => 'buyer.create', 'display_name' => 'Create Buyers', 'module' => 'Buyer Management'],
            ['name' => 'buyer.edit', 'display_name' => 'Edit Buyers', 'module' => 'Buyer Management'],
            ['name' => 'buyer.delete', 'display_name' => 'Delete Buyers', 'module' => 'Buyer Management'],

            // Product Management
            ['name' => 'product.view', 'display_name' => 'View Products', 'module' => 'Product Management'],
            ['name' => 'product.create', 'display_name' => 'Create Products', 'module' => 'Product Management'],
            ['name' => 'product.edit', 'display_name' => 'Edit Products', 'module' => 'Product Management'],
            ['name' => 'product.delete', 'display_name' => 'Delete Products', 'module' => 'Product Management'],

            // Sales Management
            ['name' => 'sale.view', 'display_name' => 'View Sales', 'module' => 'Sales Management'],
            ['name' => 'sale.create', 'display_name' => 'Create Sales', 'module' => 'Sales Management'],
            ['name' => 'sale.edit', 'display_name' => 'Edit Sales', 'module' => 'Sales Management'],
            ['name' => 'sale.delete', 'display_name' => 'Delete Sales', 'module' => 'Sales Management'],

            // Client Management
            ['name' => 'client.view', 'display_name' => 'View Clients', 'module' => 'Client Management'],
            ['name' => 'client.create', 'display_name' => 'Create Clients', 'module' => 'Client Management'],
            ['name' => 'client.edit', 'display_name' => 'Edit Clients', 'module' => 'Client Management'],
            ['name' => 'client.delete', 'display_name' => 'Delete Clients', 'module' => 'Client Management'],

            // Payment Method Management
            ['name' => 'payment_method.view', 'display_name' => 'View Payment Methods', 'module' => 'Settings'],
            ['name' => 'payment_method.create', 'display_name' => 'Create Payment Methods', 'module' => 'Settings'],
            ['name' => 'payment_method.edit', 'display_name' => 'Edit Payment Methods', 'module' => 'Settings'],
            ['name' => 'payment_method.delete', 'display_name' => 'Delete Payment Methods', 'module' => 'Settings'],

            // Product Type Management
            ['name' => 'product_type.view', 'display_name' => 'View Product Types', 'module' => 'Settings'],
            ['name' => 'product_type.create', 'display_name' => 'Create Product Types', 'module' => 'Settings'],
            ['name' => 'product_type.edit', 'display_name' => 'Edit Product Types', 'module' => 'Settings'],
            ['name' => 'product_type.delete', 'display_name' => 'Delete Product Types', 'module' => 'Settings'],

            // Product Unit Management
            ['name' => 'product_unit.view', 'display_name' => 'View Product Units', 'module' => 'Settings'],
            ['name' => 'product_unit.create', 'display_name' => 'Create Product Units', 'module' => 'Settings'],
            ['name' => 'product_unit.edit', 'display_name' => 'Edit Product Units', 'module' => 'Settings'],
            ['name' => 'product_unit.delete', 'display_name' => 'Delete Product Units', 'module' => 'Settings'],

            // Estimation Management
            ['name' => 'estimation.view', 'display_name' => 'View Estimations', 'module' => 'Settings'],
            ['name' => 'estimation.create', 'display_name' => 'Create Estimations', 'module' => 'Settings'],
            ['name' => 'estimation.edit', 'display_name' => 'Edit Estimations', 'module' => 'Settings'],
            ['name' => 'estimation.delete', 'display_name' => 'Delete Estimations', 'module' => 'Settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}