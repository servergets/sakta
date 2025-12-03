<?php

namespace App\Services;

use App\Models\NavigationGroup;
use App\Models\NavigationItem;
use Filament\Navigation\NavigationItem as FilamentNavigationItem;
use Filament\Navigation\NavigationGroup as FilamentNavigationGroup;
use Illuminate\Support\Facades\File;

class NavigationService
{
    /**
     * Build navigation dengan support untuk Filament Clusters dari database
     */
    public function buildNavigationArray(array $userRoles): array
    {
        $navigationArray = [];

        // 1. Ambil single items (non-grouped)
        $singleItems = NavigationItem::whereNull('navigation_group_id')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('cluster_path')
                      ->orWhere('cluster_path', '');
            })
            ->orderBy('sort')
            ->get()
            ->filter(fn($item) => $this->canView($item, $userRoles));

        foreach ($singleItems as $item) {
            $navGroup = FilamentNavigationGroup::make()
                ->items([$this->buildNavigationItem($item, $userRoles)]);
            
            $navigationArray[] = [
                'type' => 'group',
                'sort' => $item->sort ?? 0,
                'data' => $navGroup,
            ];
        }

        // 2. Ambil cluster-based navigation dari database
        $clusterItems = NavigationItem::whereNotNull('cluster_path')
            ->where('cluster_path', '!=', '')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort')
            ->get()
            ->groupBy('cluster_path')
            ->filter(fn($items) => $items->filter(fn($item) => $this->canView($item, $userRoles))->isNotEmpty());

        foreach ($clusterItems as $clusterPath => $items) {
            $clusterInfo = $this->getClusterInfo($clusterPath);
            $clusterChildren = [];

            foreach ($items as $item) {
                if ($this->canView($item, $userRoles)) {
                    $clusterChildren[] = $this->buildNavigationItem($item, $userRoles);
                }
            }

            if (empty($clusterChildren)) {
                continue;
            }

            $navGroup = FilamentNavigationGroup::make($clusterInfo['name'])
                ->label($clusterInfo['label'])
                ->items($clusterChildren)
                ->collapsed();

            if ($clusterInfo['icon']) {
                $navGroup->icon($clusterInfo['icon']);
            }

            $navigationArray[] = [
                'type' => 'group',
                'sort' => $clusterInfo['sort'],
                'data' => $navGroup,
            ];
        }

        // 3. Ambil regular groups
        $groups = NavigationGroup::where('is_active', true)
            ->with(['activeItems' => function ($query) {
                $query->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort');
            }])
            ->orderBy('sort')
            ->get();

        foreach ($groups as $group) {
            $groupItems = [];
            
            foreach ($group->activeItems as $item) {
                if ($this->canView($item, $userRoles)) {
                    $groupItems[] = $this->buildNavigationItem($item, $userRoles);
                }
            }

            if (empty($groupItems)) {
                continue;
            }

            $navGroup = FilamentNavigationGroup::make($group->name)
                ->label($group->label)
                ->items($groupItems)
                ->collapsed();

            if ($group->icon) {
                $navGroup->icon($group->icon);
            }

            if (isset($group->collapsible) && $group->collapsible === false) {
                $navGroup->collapsed(false);
            }

            $navigationArray[] = [
                'type' => 'group',
                'sort' => $group->sort ?? 0,
                'data' => $navGroup,
            ];
        }

        usort($navigationArray, fn($a, $b) => $a['sort'] <=> $b['sort']);

        return $navigationArray;
    }

    /**
     * Get cluster information from the actual Cluster class
     */
    protected function getClusterInfo(string $clusterPath): array
    {
        $clusterClass = "App\\Filament\\Clusters\\{$clusterPath}";
        
        if (class_exists($clusterClass)) {
            $reflection = new \ReflectionClass($clusterClass);
            $properties = $reflection->getDefaultProperties();
            
            return [
                'name' => $clusterPath,
                'label' => $properties['navigationLabel'] ?? $clusterPath,
                'icon' => $properties['navigationIcon'] ?? 'heroicon-o-folder',
                'sort' => $properties['navigationSort'] ?? 0,
            ];
        }

        // Fallback jika class tidak ada
        return [
            'name' => $clusterPath,
            'label' => $clusterPath,
            'icon' => 'heroicon-o-folder',
            'sort' => 0,
        ];
    }

    protected function buildNavigationItem(NavigationItem $item, array $userRoles = []): FilamentNavigationItem
    {
        $navItem = FilamentNavigationItem::make($item->name)
            ->label($item->label)
            ->url($item->url ?: '#')
            ->sort($item->sort ?? 0);

        if ($item->icon) {
            $navItem->icon($item->icon);
        }

        if ($item->badge) {
            $navItem->badge($item->badge);
            
            if ($item->badge_color) {
                $navItem->badgeColor($item->badge_color);
            }
        }

        $childItems = $this->buildChildItems($item, $userRoles);
        if (!empty($childItems)) {
            $navItem->childItems($childItems);
        }

        return $navItem;
    }
    
    protected function buildChildItems(NavigationItem $parentItem, array $userRoles = []): array
    {
        $children = NavigationItem::where('parent_id', $parentItem->id)
            ->where('is_active', true)
            ->where('is_visible', true)
            ->orderBy('sort')
            ->get();

        $childItems = [];
        foreach ($children as $child) {
            if ($this->canView($child, $userRoles)) {
                $childItems[] = $this->buildNavigationItem($child, $userRoles);
            }
        }

        return $childItems;
    }

    protected function canView(NavigationItem $item, array $userRoles): bool
    {
        if (empty($item->permissions)) {
            return true;
        }

        $requiredPermissions = is_array($item->permissions) 
            ? $item->permissions 
            : json_decode($item->permissions, true) ?? [];

        if (empty($requiredPermissions)) {
            return true;
        }

        return !empty(array_intersect($requiredPermissions, $userRoles));
    }
}