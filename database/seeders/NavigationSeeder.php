<?php

namespace Database\Seeders;

use App\Models\NavigationGroup;
use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Single Item - Dashboard (tanpa group)
        NavigationItem::create([
            'navigation_group_id' => null,
            'name' => 'Dashboard',
            'label' => 'Dashboard',
            'icon' => 'heroicon-o-squares-2x2',
            'url' => '/admin',
            'sort' => -10,
            'is_active' => true,
            'visible' => true,
        ]);

        // 2. Project Group
        $projectGroup = NavigationGroup::create([
            'name' => 'Project',
            'label' => 'Project',
            'icon' => 'heroicon-o-folder',
            'sort' => 1,
            'is_active' => true,
            'collapsible' => true,
        ]);

        NavigationItem::create([
            'navigation_group_id' => $projectGroup->id,
            'name' => 'List Project',
            'label' => 'List Project',
            'icon' => 'heroicon-o-briefcase',
            'url' => '/prm/projects',
            'sort' => 1,
            'is_active' => true,
            'visible' => true,
        ]);

        NavigationItem::create([
            'navigation_group_id' => $projectGroup->id,
            'name' => 'Tasks',
            'label' => 'Tasks',
            'icon' => 'heroicon-o-clipboard-document-list',
            'url' => '/prm/tasks',
            'sort' => 2,
            'is_active' => true,
            'visible' => true,
        ]);

        // 3. Transaksi Group
        $transaksiGroup = NavigationGroup::create([
            'name' => 'Transaksi',
            'label' => 'Transaksi',
            'icon' => 'heroicon-o-banknotes',
            'sort' => 2,
            'is_active' => true,
            'collapsible' => true,
        ]);

        NavigationItem::create([
            'navigation_group_id' => $transaksiGroup->id,
            'name' => 'Orders',
            'label' => 'Orders',
            'icon' => 'heroicon-o-shopping-bag',
            'url' => '/admin/orders',
            'sort' => 1,
            'is_active' => true,
            'visible' => true,
            'badge' => '5',
            'badge_color' => 'danger',
        ]);

        // 4. Single Items (Buyer, Penjualan)
        NavigationItem::create([
            'navigation_group_id' => null,
            'name' => 'Buyer',
            'label' => 'Buyer',
            'icon' => 'heroicon-o-user-circle',
            'url' => '/admin/buyers',
            'sort' => 10,
            'is_active' => true,
            'visible' => true,
        ]);

        NavigationItem::create([
            'navigation_group_id' => null,
            'name' => 'Penjualan',
            'label' => 'Penjualan',
            'icon' => 'heroicon-o-shopping-cart',
            'url' => '/admin/sales',
            'sort' => 11,
            'is_active' => true,
            'visible' => true,
        ]);

        // 5. Produk Group
        $produkGroup = NavigationGroup::create([
            'name' => 'Produk',
            'label' => 'Produk',
            'icon' => 'heroicon-o-cube',
            'sort' => 3,
            'is_active' => true,
            'collapsible' => true,
        ]);

        NavigationItem::create([
            'navigation_group_id' => $produkGroup->id,
            'name' => 'Products',
            'label' => 'Products',
            'icon' => 'heroicon-o-cube',
            'url' => '/admin/products',
            'sort' => 1,
            'is_active' => true,
            'visible' => true,
        ]);

        NavigationItem::create([
            'navigation_group_id' => $produkGroup->id,
            'name' => 'Categories',
            'label' => 'Categories',
            'icon' => 'heroicon-o-tag',
            'url' => '/admin/categories',
            'sort' => 2,
            'is_active' => true,
            'visible' => true,
        ]);

        // 6. Settings (single item - paling bawah)
        NavigationItem::create([
            'navigation_group_id' => null,
            'name' => 'Settings',
            'label' => 'Settings',
            'icon' => 'heroicon-o-cog-6-tooth',
            'url' => '/admin/settings',
            'sort' => 100,
            'is_active' => true,
            'visible' => true,
            'roles' => ['admin'], // Hanya admin yang bisa lihat
        ]);
    }
}