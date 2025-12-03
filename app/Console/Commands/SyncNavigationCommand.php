<?php

namespace App\Console\Commands;

use App\Models\NavigationGroup;
use App\Models\NavigationItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use Spatie\Permission\Models\Permission;

class SyncNavigationCommand extends Command
{
    protected $signature = 'navigation:sync 
                            {--fresh : Delete existing items before sync}
                            {--create-permissions : Auto-create missing permissions}';

    protected $description = 'Sync navigation from Filament Resources to database (Spatie integration)';

    public function handle()
    {
        if ($this->option('fresh')) {
            if ($this->confirm('This will delete all existing navigation items. Continue?')) {
                NavigationItem::query()->delete();
                NavigationGroup::query()->delete();
                $this->info('Existing navigation cleared.');
            } else {
                return 0;
            }
        }

        $this->info('🔍 Scanning Filament Resources...');

        $resources = $this->getFilamentResources();
        $syncedCount = 0;
        $groupsCreated = [];

        foreach ($resources as $resource) {
            try {
                $reflection = new ReflectionClass($resource);

                // Skip abstract classes
                if ($reflection->isAbstract()) {
                    continue;
                }

                // Get navigation properties
                $navigationGroup = $this->getStaticProperty($resource, 'navigationGroup');
                $navigationLabel = $this->getStaticProperty($resource, 'navigationLabel') 
                    ?? $this->getResourceLabel($resource);
                $navigationIcon = $this->getStaticProperty($resource, 'navigationIcon');
                $navigationSort = $this->getStaticProperty($resource, 'navigationSort') ?? 0;
                $navigationUrl = $this->getResourceUrl($resource);

                // 👇 AUTO-DETECT PERMISSION from Resource
                $permission = $this->detectPermission($resource, $navigationLabel);

                // Auto-create permission if flag is set
                if ($permission && $this->option('create-permissions')) {
                    Permission::firstOrCreate(['name' => $permission]);
                    $this->line("  📝 Permission created/found: {$permission}");
                }

                // Create/Get Navigation Group
                $groupId = null;
                if ($navigationGroup) {
                    if (!isset($groupsCreated[$navigationGroup])) {
                        $groupPermission = $this->guessGroupPermission($navigationGroup);
                        
                        $group = NavigationGroup::firstOrCreate(
                            ['name' => $navigationGroup],
                            [
                                'label' => $navigationGroup,
                                'icon' => $this->guessGroupIcon($navigationGroup),
                                'sort' => count($groupsCreated) + 1,
                                'is_active' => true,
                                'permissions' => $groupPermission, // 👈 Set permission
                            ]
                        );
                        $groupsCreated[$navigationGroup] = $group->id;
                        $this->info("  📁 Group: {$navigationGroup} (permission: {$groupPermission})");
                    }
                    $groupId = $groupsCreated[$navigationGroup];
                }

                // Create/Update Navigation Item
                $item = NavigationItem::updateOrCreate(
                    [
                        'name' => class_basename($resource),
                        'url' => $navigationUrl,
                    ],
                    [
                        'navigation_group_id' => $groupId,
                        'label' => $navigationLabel,
                        'icon' => $navigationIcon,
                        'resource' => $resource,
                        'sort' => $navigationSort,
                        'is_active' => true,
                        'is_visible' => true,
                        'permissions' => $permission, // 👈 Set permission from detection
                    ]
                );

                $permLabel = $permission ? "✅ {$permission}" : "⚠️ no permission";
                $this->line("  {$navigationLabel} → {$navigationUrl} ({$permLabel})");
                $syncedCount++;

            } catch (\Exception $e) {
                $this->error("  ✗ Failed: " . class_basename($resource));
                $this->error("    " . $e->getMessage());
            }
        }

        $this->info("\n✅ Synced {$syncedCount} navigation items.");
        $this->info("📁 Created/Updated " . count($groupsCreated) . " groups.");

        // Show items without permissions
        $noPermission = NavigationItem::whereNull('permissions')->count();
        if ($noPermission > 0) {
            $this->warn("\n⚠️  {$noPermission} items without permission (visible to all)");
            $this->line("Run with --create-permissions to auto-create them");
        }

        return 0;
    }

    /**
     * Auto-detect permission from Resource
     */
    protected function detectPermission(string $resource, string $label): ?string
    {
        // Check if Resource has canViewAny method
        if (method_exists($resource, 'canViewAny')) {
            // Try to extract permission from method source
            // This is basic detection, might need improvement
        }

        // Generate permission from label
        $module = str_replace(['Resource', 'Management'], '', class_basename($resource));
        $module = str($module)->snake()->plural()->toString();
        
        return "view_{$module}";
    }

    /**
     * Guess group permission
     */
    protected function guessGroupPermission(string $groupName): string
    {
        $mapping = [
            'Projects' => 'view_projects',
            'Project' => 'view_projects',
            'Sales' => 'view_sales',
            'Sales & Transactions' => 'view_sales',
            'Contacts' => 'view_buyers',
            'Products' => 'view_products',
            'Product' => 'view_products',
            'User Management' => 'view_users',
            'Settings' => 'view_settings',
        ];

        if (isset($mapping[$groupName])) {
            return $mapping[$groupName];
        }

        // Generate from group name
        $name = str($groupName)->snake()->toString();
        return "view_{$name}";
    }

    protected function getFilamentResources(): array
    {
        $resources = [];
        $paths = [
            app_path('Filament/Resources'),
            app_path('Filament/Pages'),
        ];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                continue;
            }

            $files = File::allFiles($path);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $relativePath = str_replace(app_path(), '', $file->getPathname());
                $relativePath = str_replace(['/', '.php'], ['\\', ''], $relativePath);
                $class = 'App' . $relativePath;

                if (class_exists($class)) {
                    $resources[] = $class;
                }
            }
        }

        return $resources;
    }

    protected function getStaticProperty(string $class, string $property)
    {
        try {
            $reflection = new ReflectionClass($class);
            
            if ($reflection->hasProperty($property)) {
                $prop = $reflection->getProperty($property);
                $prop->setAccessible(true);
                return $prop->getValue();
            }

            $method = 'get' . ucfirst($property);
            if ($reflection->hasMethod($method)) {
                return $class::$method();
            }

        } catch (\Exception $e) {
            //
        }

        return null;
    }

    protected function getResourceLabel(string $resource): string
    {
        if (method_exists($resource, 'getNavigationLabel')) {
            return $resource::getNavigationLabel();
        }

        $name = class_basename($resource);
        $name = str_replace('Resource', '', $name);
        return str($name)->headline()->toString();
    }

    protected function getResourceUrl(string $resource): string
    {
        if (method_exists($resource, 'getUrl')) {
            try {
                return $resource::getUrl();
            } catch (\Exception $e) {
                //
            }
        }

        $name = class_basename($resource);
        $name = str_replace('Resource', '', $name);
        $slug = str($name)->kebab()->plural()->toString();
        
        return "/prm/{$slug}";
    }

    protected function guessGroupIcon(string $groupName): string
    {
        $icons = [
            'Project' => 'heroicon-o-folder',
            'Projects' => 'heroicon-o-folder',
            'Transaksi' => 'heroicon-o-banknotes',
            'Transaction' => 'heroicon-o-banknotes',
            'Sales' => 'heroicon-o-shopping-cart',
            'Produk' => 'heroicon-o-cube',
            'Product' => 'heroicon-o-cube',
            'Products' => 'heroicon-o-cube',
            'Contacts' => 'heroicon-o-users',
            'User' => 'heroicon-o-users',
            'Users' => 'heroicon-o-users',
            'User Management' => 'heroicon-o-shield-check',
            'Settings' => 'heroicon-o-cog-6-tooth',
            'Setting' => 'heroicon-o-cog-6-tooth',
            'Report' => 'heroicon-o-document-chart-bar',
            'Reports' => 'heroicon-o-document-chart-bar',
        ];

        return $icons[$groupName] ?? 'heroicon-o-folder';
    }
}