<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\NavigationGroup;
use App\Models\NavigationItem;

class CompleteSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🚀 Starting Complete Setup...');

        // ============================================
        // STEP 1: CREATE PERMISSIONS
        // ============================================
        $this->command->info('📝 Creating Permissions...');

        $permissions = [
            // Dashboard
            'view_dashboard',
            
            // Projects
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            
            // Transactions
            'view_transactions',
            'create_transactions',
            'edit_transactions',
            'delete_transactions',
            
            // Buyers
            'view_buyers',
            'create_buyers',
            'edit_buyers',
            'delete_buyers',
            
            // Clients
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            
            // Products
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Product Types
            'view_product_types',
            'create_product_types',
            'edit_product_types',
            'delete_product_types',
            
            // Product Units
            'view_product_units',
            'create_product_units',
            'edit_product_units',
            'delete_product_units',
            
            // Estimations
            'view_estimations',
            'create_estimations',
            'edit_estimations',
            'delete_estimations',
            
            // Sales
            'view_sales',
            'create_sales',
            'edit_sales',
            'delete_sales',
            
            // Payment Methods
            'view_payment_methods',
            'create_payment_methods',
            'edit_payment_methods',
            'delete_payment_methods',
            
            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Roles & Permissions
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            
            // Settings
            'view_settings',
            'edit_settings',
            
            // Navigation
            'view_navigation',
            'edit_navigation',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->command->info('✅ Created ' . count($permissions) . ' permissions');

        // ============================================
        // STEP 2: ASSIGN PERMISSIONS TO ROLES
        // ============================================
        $this->command->info('👥 Assigning Permissions to Roles...');

        // Super Admin - ALL permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info('✅ Super Admin: ' . Permission::count() . ' permissions');

        // Admin - Most permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->syncPermissions([
            'view_dashboard',
            'view_projects', 'create_projects', 'edit_projects', 'delete_projects',
            'view_transactions', 'create_transactions', 'edit_transactions',
            'view_buyers', 'create_buyers', 'edit_buyers',
            'view_clients', 'create_clients', 'edit_clients',
            'view_products', 'create_products', 'edit_products',
            'view_product_types', 'create_product_types', 'edit_product_types',
            'view_product_units', 'create_product_units', 'edit_product_units',
            'view_estimations', 'create_estimations', 'edit_estimations',
            'view_sales', 'create_sales', 'edit_sales',
            'view_payment_methods',
            'view_users',
            'view_settings',
        ]);
        $this->command->info('✅ Admin: ' . $admin->permissions->count() . ' permissions');

        // Manager
        $manager = Role::create(['name' => 'manager']);
        $manager->syncPermissions([
            'view_dashboard',
            'view_projects', 'edit_projects',
            'view_transactions', 'edit_transactions',
            'view_buyers', 'view_clients',
            'view_products',
            'view_estimations', 'create_estimations', 'edit_estimations',
            'view_sales',
        ]);
        $this->command->info('✅ Manager: ' . $manager->permissions->count() . ' permissions');

        // Staff
        $staff = Role::create(['name' => 'staff']);
        $staff->syncPermissions([
            'view_dashboard',
            'view_projects',
            'view_transactions',
            'view_buyers', 'view_clients',
            'view_products',
            'view_estimations',
            'view_sales',
        ]);
        $this->command->info('✅ Staff: ' . $staff->permissions->count() . ' permissions');

        // Sales
        $sales = Role::create(['name' => 'sales']);
        $sales->syncPermissions([
            'view_dashboard',
            'view_buyers', 'create_buyers', 'edit_buyers',
            'view_clients', 'create_clients', 'edit_clients',
            'view_products',
            'view_sales', 'create_sales', 'edit_sales',
            'view_transactions', 'create_transactions',
        ]);
        $this->command->info('✅ Sales: ' . $sales->permissions->count() . ' permissions');

        // Viewer
        $viewer = Role::create(['name' => 'viewer']);
        $viewer->syncPermissions([
            'view_dashboard',
            'view_projects',
            'view_transactions',
            'view_buyers',
            'view_clients',
            'view_products',
            'view_sales',
        ]);
        $this->command->info('✅ Viewer: ' . $viewer->permissions->count() . ' permissions');

        // ============================================
        // STEP 3: CLEAR OLD NAVIGATION
        // ============================================
        $this->command->info('🗑️  Clearing old navigation...');
        NavigationItem::query()->delete();
        NavigationGroup::query()->delete();

        // ============================================
        // STEP 4: CREATE NAVIGATION STRUCTURE
        // ============================================
        $this->command->info('🧭 Creating Navigation...');

        // Dashboard (single item, top)
        NavigationItem::create([
            'name' => 'Dashboard',
            'label' => 'Dashboard',
            'icon' => 'heroicon-o-home',
            'url' => '/admin',
            'sort' => -10,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_dashboard',
        ]);

        // === PROJECT GROUP ===
        $projectGroup = NavigationGroup::create([
            'name' => 'Projects',
            'label' => 'Projects',
            'icon' => 'heroicon-o-folder',
            'sort' => 1,
            'is_active' => true,
            'permissions' => 'view_projects',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $projectGroup->id,
            'name' => 'Projects',
            'label' => 'All Projects',
            'icon' => 'heroicon-o-briefcase',
            'url' => '/admin/projects',
            'sort' => 1,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_projects',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $projectGroup->id,
            'name' => 'Estimations',
            'label' => 'Estimations',
            'icon' => 'heroicon-o-calculator',
            'url' => '/admin/estimations',
            'sort' => 2,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_estimations',
        ]);

        // === SALES GROUP ===
        $salesGroup = NavigationGroup::create([
            'name' => 'Sales',
            'label' => 'Sales & Transactions',
            'icon' => 'heroicon-o-banknotes',
            'sort' => 2,
            'is_active' => true,
            'permissions' => 'view_sales',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $salesGroup->id,
            'name' => 'Sales',
            'label' => 'Sales',
            'icon' => 'heroicon-o-shopping-cart',
            'url' => '/admin/sales',
            'sort' => 1,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_sales',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $salesGroup->id,
            'name' => 'Transactions',
            'label' => 'Transactions',
            'icon' => 'heroicon-o-credit-card',
            'url' => '/admin/transactions',
            'sort' => 2,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_transactions',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $salesGroup->id,
            'name' => 'PaymentMethods',
            'label' => 'Payment Methods',
            'icon' => 'heroicon-o-wallet',
            'url' => '/admin/payment-methods',
            'sort' => 3,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_payment_methods',
        ]);

        // === CONTACTS GROUP ===
        $contactsGroup = NavigationGroup::create([
            'name' => 'Contacts',
            'label' => 'Contacts',
            'icon' => 'heroicon-o-users',
            'sort' => 3,
            'is_active' => true,
            'permissions' => 'view_buyers', // Any contact permission
        ]);

        NavigationItem::create([
            'navigation_group_id' => $contactsGroup->id,
            'name' => 'Buyers',
            'label' => 'Buyers',
            'icon' => 'heroicon-o-user-circle',
            'url' => '/admin/buyers',
            'sort' => 1,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_buyers',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $contactsGroup->id,
            'name' => 'Clients',
            'label' => 'Clients',
            'icon' => 'heroicon-o-user-group',
            'url' => '/admin/clients',
            'sort' => 2,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_clients',
        ]);

        // === PRODUCTS GROUP ===
        $productsGroup = NavigationGroup::create([
            'name' => 'Products',
            'label' => 'Products',
            'icon' => 'heroicon-o-cube',
            'sort' => 4,
            'is_active' => true,
            'permissions' => 'view_products',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $productsGroup->id,
            'name' => 'Products',
            'label' => 'All Products',
            'icon' => 'heroicon-o-cube',
            'url' => '/admin/products',
            'sort' => 1,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_products',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $productsGroup->id,
            'name' => 'ProductTypes',
            'label' => 'Product Types',
            'icon' => 'heroicon-o-tag',
            'url' => '/admin/product-types',
            'sort' => 2,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_product_types',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $productsGroup->id,
            'name' => 'ProductUnits',
            'label' => 'Product Units',
            'icon' => 'heroicon-o-scale',
            'url' => '/admin/product-units',
            'sort' => 3,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_product_units',
        ]);

        // === USER MANAGEMENT GROUP (Admin only) ===
        $userMgmtGroup = NavigationGroup::create([
            'name' => 'UserManagement',
            'label' => 'User Management',
            'icon' => 'heroicon-o-shield-check',
            'sort' => 5,
            'is_active' => true,
            'permissions' => 'view_users',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $userMgmtGroup->id,
            'name' => 'Users',
            'label' => 'Users',
            'icon' => 'heroicon-o-users',
            'url' => '/admin/users',
            'sort' => 1,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_users',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $userMgmtGroup->id,
            'name' => 'Roles',
            'label' => 'Roles',
            'icon' => 'heroicon-o-shield-check',
            'url' => '/admin/roles',
            'sort' => 2,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_roles',
        ]);

        NavigationItem::create([
            'navigation_group_id' => $userMgmtGroup->id,
            'name' => 'Permissions',
            'label' => 'Permissions',
            'icon' => 'heroicon-o-key',
            'url' => '/admin/permissions',
            'sort' => 3,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_roles',
        ]);

        // === SETTINGS (Bottom, single item) ===
        NavigationItem::create([
            'name' => 'Settings',
            'label' => 'Settings',
            'icon' => 'heroicon-o-cog-6-tooth',
            'url' => '/admin/settings',
            'sort' => 100,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_settings',
        ]);

        NavigationItem::create([
            'name' => 'Navigation',
            'label' => 'Navigation',
            'icon' => 'heroicon-o-bars-3',
            'url' => '/admin/navigation-items',
            'sort' => 101,
            'is_active' => true,
            'is_visible' => true,
            'permissions' => 'view_navigation',
        ]);

        $this->command->info('✅ Created Navigation Structure');

        // ============================================
        // STEP 5: SUMMARY
        // ============================================
        $this->command->info('');
        $this->command->info('✅ ============================================');
        $this->command->info('✅ SETUP COMPLETE!');
        $this->command->info('✅ ============================================');
        $this->command->info('📊 Permissions: ' . Permission::count());
        $this->command->info('👥 Roles: ' . Role::count());
        $this->command->info('📁 Navigation Groups: ' . NavigationGroup::count());
        $this->command->info('🧭 Navigation Items: ' . NavigationItem::count());
        $this->command->info('');
        $this->command->info('🔐 Role Permissions:');
        foreach (Role::with('permissions')->get() as $role) {
            $this->command->info("   {$role->name}: {$role->permissions->count()} permissions");
        }
        $this->command->info('');
        $this->command->info('🎉 You can now login and see the navigation menu!');
    }
}