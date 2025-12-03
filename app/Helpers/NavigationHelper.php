<?php

namespace App\Helpers;

use App\Models\NavigationItem;
use App\Models\NavigationGroup;
use Illuminate\Support\Facades\Cache;

class NavigationHelper
{
    /**
     * Update badge untuk navigation item
     */
    public static function updateBadge(string $itemName, $badge, ?string $color = null): void
    {
        $item = NavigationItem::where('name', $itemName)->first();
        
        if ($item) {
            $item->update([
                'badge' => (string) $badge,
                'badge_color' => $color ?? $item->badge_color,
            ]);
            
            self::clearCache();
        }
    }

    /**
     * Update multiple badges sekaligus
     */
    public static function updateBadges(array $badges): void
    {
        foreach ($badges as $itemName => $config) {
            if (is_array($config)) {
                self::updateBadge($itemName, $config['badge'], $config['color'] ?? null);
            } else {
                self::updateBadge($itemName, $config);
            }
        }
    }

    /**
     * Toggle visibility item
     */
    public static function toggleVisibility(string $itemName, bool $visible = true): void
    {
        NavigationItem::where('name', $itemName)->update(['visible' => $visible]);
        self::clearCache();
    }

    /**
     * Toggle active status
     */
    public static function toggleActive(string $itemName, bool $active = true): void
    {
        NavigationItem::where('name', $itemName)->update(['is_active' => $active]);
        self::clearCache();
    }

    /**
     * Add navigation item programmatically
     */
    public static function addItem(array $data): NavigationItem
    {
        $item = NavigationItem::create(array_merge([
            'is_active' => true,
            'visible' => true,
            'sort' => 0,
        ], $data));
        
        self::clearCache();
        
        return $item;
    }

    /**
     * Add navigation group programmatically
     */
    public static function addGroup(array $data): NavigationGroup
    {
        $group = NavigationGroup::create(array_merge([
            'is_active' => true,
            'collapsible' => true,
            'sort' => 0,
        ], $data));
        
        self::clearCache();
        
        return $group;
    }

    /**
     * Remove navigation item
     */
    public static function removeItem(string $itemName): void
    {
        NavigationItem::where('name', $itemName)->delete();
        self::clearCache();
    }

    /**
     * Get navigation item by name
     */
    public static function getItem(string $itemName): ?NavigationItem
    {
        return NavigationItem::where('name', $itemName)->first();
    }

    /**
     * Get navigation group by name
     */
    public static function getGroup(string $groupName): ?NavigationGroup
    {
        return NavigationGroup::where('name', $groupName)->first();
    }

    /**
     * Reorder items in a group
     */
    public static function reorderGroup(string $groupName, array $itemOrder): void
    {
        $group = self::getGroup($groupName);
        
        if (!$group) {
            return;
        }

        foreach ($itemOrder as $index => $itemName) {
            NavigationItem::where('navigation_group_id', $group->id)
                ->where('name', $itemName)
                ->update(['sort' => $index + 1]);
        }
        
        self::clearCache();
    }

    /**
     * Clear navigation cache
     */
    public static function clearCache(): void
    {
        Cache::tags(['navigation'])->flush();
        // Or if not using tags:
        // Cache::forget('navigation.*');
    }

    /**
     * Get all active navigation items for a user
     */
    public static function getNavigationForUser($user): array
    {
        $userRoles = self::getUserRoles($user);
        
        return NavigationItem::active()
            ->with('group')
            ->ordered()
            ->get()
            ->filter(fn($item) => $item->canView($userRoles))
            ->groupBy('navigation_group_id')
            ->toArray();
    }

    /**
     * Get user roles helper
     */
    protected static function getUserRoles($user): array
    {
        if (!$user) {
            return [];
        }

        if (method_exists($user, 'getRoleNames')) {
            return $user->getRoleNames()->toArray();
        }

        if (isset($user->roles)) {
            return is_string($user->roles) 
                ? [$user->roles] 
                : $user->roles->pluck('name')->toArray();
        }

        return [];
    }

    /**
     * Sync badges from callbacks
     * Usage: NavigationHelper::syncDynamicBadges()
     */
    public static function syncDynamicBadges(): void
    {
        $badges = [
            'Orders' => [
                'callback' => fn() => \App\Models\Order::pending()->count(),
                'color' => 'danger',
            ],
            'Messages' => [
                'callback' => fn() => \App\Models\Message::unread()->count(),
                'color' => 'warning',
            ],
            'Notifications' => [
                'callback' => fn() => auth()->user()?->unreadNotifications()->count() ?? 0,
                'color' => 'info',
            ],
        ];

        foreach ($badges as $itemName => $config) {
            $count = $config['callback']();
            
            if ($count > 0) {
                self::updateBadge($itemName, $count, $config['color']);
            } else {
                self::updateBadge($itemName, null, null);
            }
        }
    }

    /**
     * Export navigation configuration
     */
    public static function export(): array
    {
        return [
            'groups' => NavigationGroup::with('items')->get()->toArray(),
            'items' => NavigationItem::whereNull('navigation_group_id')->get()->toArray(),
        ];
    }

    /**
     * Import navigation configuration
     */
    public static function import(array $config): void
    {
        // Clear existing
        NavigationItem::query()->delete();
        NavigationGroup::query()->delete();

        // Import groups
        if (isset($config['groups'])) {
            foreach ($config['groups'] as $groupData) {
                $items = $groupData['items'] ?? [];
                unset($groupData['items'], $groupData['id'], $groupData['created_at'], $groupData['updated_at']);
                
                $group = NavigationGroup::create($groupData);
                
                foreach ($items as $itemData) {
                    unset($itemData['id'], $itemData['created_at'], $itemData['updated_at']);
                    $itemData['navigation_group_id'] = $group->id;
                    NavigationItem::create($itemData);
                }
            }
        }

        // Import standalone items
        if (isset($config['items'])) {
            foreach ($config['items'] as $itemData) {
                unset($itemData['id'], $itemData['created_at'], $itemData['updated_at']);
                NavigationItem::create($itemData);
            }
        }

        self::clearCache();
    }
}