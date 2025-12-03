<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Services\NavigationService;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Widgets;
use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\CheckPermission;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\Width;
use Illuminate\Support\HtmlString;


// TAMBAHKAN IMPORT INI
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class PrmPanelProvider extends PanelProvider
{
     public function boot(): void
    {
        // Konfigurasi global untuk CreateAction
        CreateAction::configureUsing(function (CreateAction $action) {
            return $action
                ->modalHeading(fn ($action) => 'Tambah ' . ($action->getModelLabel() ?? 'Data'))
                ->modalSubmitActionLabel('Simpan')
                ->modalCancelActionLabel('Batal')
                ->modalSubmitAction(fn ($action) => $action->color('primary'))
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right')
                ->icon('heroicon-o-plus');
        });

        // Konfigurasi global untuk EditAction
        EditAction::configureUsing(function (EditAction $action) {
            return $action
                ->modalHeading(fn ($action) => 'Edit ' . ($action->getModelLabel() ?? 'Data'))
                ->modalSubmitActionLabel('Simpan')
                ->modalCancelActionLabel('Batal')
                ->modalSubmitAction(fn ($action) => $action->color('primary'))
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right')
                ->icon('heroicon-o-pencil')
                ->iconButton();
        });

        // Konfigurasi global untuk DeleteAction
        DeleteAction::configureUsing(function (DeleteAction $action) {
            return $action
                ->modalHeading(fn ($action) => 'Hapus ' . ($action->getModelLabel() ?? 'Data'))
                ->modalSubmitActionLabel('Hapus')
                ->modalCancelActionLabel('Batal')
                ->modalSubmitAction(fn ($action) => $action->color('danger'))
                ->modalCancelAction(fn ($action) => $action->outlined())
                ->modalFooterActionsAlignment('right')
                ->icon('heroicon-o-trash')                
                ->iconButton();
        });
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('prm')
            ->path('prm')
            ->login(Login::class)
            // ->navigationGroups([
            //     NavigationGroup::make()
            //         ->label('Project')
            //         ->icon(Heroicon::Briefcase),
            //     NavigationGroup::make()
            //         ->label('Transaksi')
            //         ->icon(Heroicon::Banknotes),
            //     NavigationGroup::make()
            //         ->label('Produk')
            //         ->icon(Heroicon::Inbox),
            // ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-squares-2x2')
                            ->url('/prm')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.prm.pages.dashboard') || request()->is('prm'))
                            ->sort(1),
                    ])
                    ->groups([
                        NavigationGroup::make('Project')
                            ->label('Project')
                            ->icon('heroicon-o-briefcase')
                            ->collapsed(true)
                            ->items([
                                NavigationItem::make('List Project')
                                    ->label('List Project')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/projects'))
                                    ->url('/prm/projects'),
                                NavigationItem::make('Riwayat Project')
                                    ->label('Riwayat Project')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/projects/project-histories*'))
                                    ->url('/prm/projects/project-histories'),
                            ]),
                            
                        // 3. Transaksi Group  
                        NavigationGroup::make('Transaksi')
                            ->label('Transaksi')
                            ->icon('heroicon-o-banknotes')
                            ->collapsed(true)
                            ->items([
                                NavigationItem::make('Transaksi Project')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/transactions/transaction-projects*'))
                                    ->url('/prm/transactions/transaction-projects'),
                                NavigationItem::make('Transaksi Produk')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/transactions/transaction-products*'))
                                    ->url('/prm/transactions/transaction-products'),
                                NavigationItem::make('Margin Buyer')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/transactions/margin-buyers*'))
                                    ->url('/prm/transactions/margin-buyers'),
                            ]),
                            
                        // 4. Single items (tanpa group)
                        NavigationGroup::make() // Group kosong untuk single items
                            ->items([
                                NavigationItem::make('Buyer')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/buyers*'))
                                    ->url('/prm/buyers'),
                            ]),
                             
                        NavigationGroup::make('Produk')
                            ->label('Produk')
                            ->icon('heroicon-o-inbox-stack')
                            ->collapsed(true)
                            ->items([
                                NavigationItem::make('Produk Aktif')
                                    ->label('Produk Aktif')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/products/active-products'))
                                    ->url('/prm/products/active-products'),
                                NavigationItem::make('Mutasi Stok')
                                    ->label('Mutasi Stok')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/products/product-mutations'))
                                    ->url('/prm/products/product-mutations'),
                                NavigationItem::make('Master Produk')
                                    ->label('Master Produk')
                                    ->icon('heroicon-o-user-circle')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/products'))
                                    ->url('/prm/products'),
                            ]),
                        NavigationGroup::make() // Group kosong untuk single items
                            ->items([
                                NavigationItem::make('Penjualan')
                                    ->icon('heroicon-o-shopping-cart')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/sales*'))
                                    ->url('/prm/sales'),
                            ]),
                        // 5. Produk Group
                        NavigationGroup::make('Produk')
                            ->label('Produk')
                            ->icon('heroicon-o-cube'),
                            
                        NavigationGroup::make()
                            ->items([
                                NavigationItem::make('Settings')
                                    ->icon('heroicon-o-cog-6-tooth')
                                    ->isActiveWhen(fn (): bool => request()->is('prm/settings*'))
                                    ->url('/prm/settings'),
                            ]),
                    ]);
            })
            ->colors([
                'primary' => Color::Teal,
                // 'gray' => Color::Slate,
            ])
            ->breadcrumbs(false)
            // ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            //     $navigationService = app(NavigationService::class);
            //     $userRoles = $this->getUserRoles();
                
            //     $navigationArray = $navigationService->buildNavigationArray($userRoles);
                
            //     // Untuk MIXED ORDER: semua sebagai groups
            //     $groups = array_map(fn($nav) => $nav['data'], $navigationArray);
                
            //     return $builder->groups($groups);
                
            //     // Untuk DEFAULT ORDER: pisahkan items dan groups
            //     // $items = [];
            //     // $groups = [];
            //     // foreach ($navigationArray as $nav) {
            //     //     if ($nav['type'] === 'item') {
            //     //         $items[] = $nav['data'];
            //     //     } elseif ($nav['type'] === 'group') {
            //     //         $groups[] = $nav['data'];
            //     //     }
            //     // }
            //     // return $builder->items($items)->groups($groups);
            // })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                \App\Filament\Widgets\DashboardStatsWidget::class,
                \App\Filament\Widgets\SalesChartWidget::class,
                \App\Filament\Widgets\RecentSalesWidget::class,
                \App\Filament\Widgets\ProjectStatusWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckUserActive::class,
            ])
            // ->navigationGroups([
            //     'Project Management',
            //     'Settings',
            // ])
            ->brandName('SAKTA')
            ->favicon(asset('images/favicon.ico'))
            ->sidebarCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook('panels::head.end', fn() => new HtmlString('
                <style>
                    .fi-sidebar-nav-groups {
                        row-gap: 0.2rem !important;
                    }
                    .fi-sidebar-nav {
                        background: white !important;
                    }
                    .dark .fi-sidebar-nav {
                        background: black !important;
                    }
                </style>
            '))
            ->simplePageMaxContentWidth(Width::Small);
    }
    
    protected function getUserRoles(): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return [];
        }

        // Jika pakai Spatie Permission
        if (method_exists($user, 'getRoleNames')) {
            return $user->getRoleNames()->toArray();
        }

        // Jika pakai custom roles
        if (isset($user->roles)) {
            return is_string($user->roles) 
                ? [$user->roles] 
                : $user->roles->pluck('name')->toArray();
        }

        // Default role
        return ['user'];
    }
}
