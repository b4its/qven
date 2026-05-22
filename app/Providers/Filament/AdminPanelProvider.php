<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Admin\AdminCalonPenerimas\AdminCalonPenerimaResource;
use App\Filament\Resources\Admin\AdminKotakMBGS\AdminKotakMBGResource;
use App\Filament\Resources\Admin\AdminUserKaryawans\AdminUserKaryawanResource;
use App\Filament\Resources\Admin\AdminUserKaryawans\Schemas\AdminUserKaryawanForm;
use App\Filament\Resources\Admin\AdminUserPenerimas\AdminUserPenerimaResource;
use App\Filament\Widgets\Admin\AdminStatsOverview;
use App\Models\Vendor;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandNames = 'Admin Panel';
        $logoPath = asset('assets/logo/origin_oryphem_black.png');
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandName($brandNames)
            ->brandLogo(fn() => view('filament.components.brand-logo', [
                'logoPath' => $logoPath, 
                'brandNames' => $brandNames, 
                'instansiName' => Auth::user()?->instansiPenerima ? "- " . Auth::user()->instansiPenerima->name : "- "
            ]))
            ->login()
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url('#edit-profile'),
            ])
            ->tenant(Vendor::class, slugAttribute: 'id') // URL akan menjadi /admin/1/dashboard, /admin/2/dst
            ->tenantMenu(true)
            ->renderHook(
                'panels::body.end',
                fn (): string => Blade::render('@livewire(\App\Livewire\EditProfileModal::class)')
            )
            ->renderHook(
                'panels::head.end', 
                fn () => view('filament.hooks.custom-favicon', ['logoPath' => $logoPath]),
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn () => view('filament.hooks.halaman-utama-button'),
            )

            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        // 1. Dashboard selalu di atas
                        ...Dashboard::getNavigationItems(),
                    ])
                    ->groups([
                        // 2. Grup Akun di urutan kedua
                        NavigationGroup::make('Akun')
                            ->items([
                                ...AdminUserKaryawanResource::getNavigationItems(),
                                ...AdminUserPenerimaResource::getNavigationItems(),
                            ]),
                        
                        // 3. Grup Kosong (tanpa label) di urutan terbawah
                        NavigationGroup::make('') 
                            ->items([
                                ...AdminCalonPenerimaResource::getNavigationItems(),
                                ...AdminKotakMBGResource::getNavigationItems(),
                                
                                // Menu custom manual
                                NavigationItem::make('Landing Page')
                                    ->url(fn () => route('welcome'))
                                    ->icon('heroicon-o-globe-alt'),

                                NavigationItem::make('Buku Panduan Admin')
                                    ->url(fn () => asset('panduan/BukuPanduan_Admin.pdf'))
                                    ->icon('heroicon-o-book-open') 
                                    ->sort(10)
                                    ->openUrlInNewTab(),
                            ]),
                    ]);
            })
            
            ->globalSearch(false)
            ->discoverResources(in: app_path('Filament/Resources/Admin'), for: 'App\Filament\Resources\Admin')
            ->discoverPages(in: app_path('Filament/Pages/Admin'), for: 'App\Filament\Pages\Admin')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Admin'), for: 'App\Filament\Widgets\Admin')
            ->widgets([
                AdminStatsOverview::class,
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
            ]);
    }
}
