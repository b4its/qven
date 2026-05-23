<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Superadmin\SuperadminAktifitas\SuperadminAktifitasResource;
use App\Filament\Resources\Superadmin\SuperadminCalonPenerimas\SuperadminCalonPenerimaResource;
use App\Filament\Resources\Superadmin\SuperadminInstansiPenerimas\SuperadminInstansiPenerimaResource;
use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\SuperadminKotakMBGResource;
use App\Filament\Resources\Superadmin\SuperadminUserAdmins\SuperadminUserAdminResource;
use App\Filament\Resources\Superadmin\SuperadminUserKaryawans\SuperadminUserKaryawanResource;
use App\Filament\Resources\Superadmin\SuperadminUserPenerimas\SuperadminUserPenerimaResource;
use App\Filament\Resources\Superadmin\SuperadminVendors\SuperadminVendorResource;
use App\Filament\Widgets\Superadmin\SuperadminStatsOverview;
use App\Filament\Widgets\Superadmin\SuperadminTable;
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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SuperadminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandNames = 'Superadmin Panel';
        $logoPath = asset('assets/logo/Logo-mono.png');

        return $panel
            ->id('superadmin')
            ->path('superadmin')
            ->viteTheme('resources/css/filament/superadmin/theme.css')
            ->colors([
                'primary' => Color::Amber,
                'royal'     => '#4f46e5', // Biru keunguan mewah
                'emerald'   => '#10b981', // Hijau perhiasan
                'ocean'     => '#0ea5e9', // Biru laut cerah
                'sunshine'  => '#f59e0b', // Kuning hangat
                'crimson'   => '#e11d48', // Merah gelap elegan
                'slate'     => '#475569', // Abu-abu kebiruan profesional
                'night'     => '#1e293b', // Gelap pekat
                'cyan'  => '#3fbde4', // Ungu modern
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
            // KONTROL PENUH NAVIGASI DI SINI
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        // 1. Dashboard selalu di atas
                        ...Dashboard::getNavigationItems(),
                        ...SuperadminVendorResource::getNavigationItems(),
                        ...SuperadminInstansiPenerimaResource::getNavigationItems(),
                    ])
                    ->groups([
                        // 2. Grup Akun di urutan kedua
                        NavigationGroup::make('Akun')
                            ->items([
                                ...SuperadminUserAdminResource::getNavigationItems(),
                                ...SuperadminUserKaryawanResource::getNavigationItems(),
                                ...SuperadminUserPenerimaResource::getNavigationItems(),
                            ]),
                        
                        // 3. Grup Kosong (tanpa label) di urutan terbawah
                        NavigationGroup::make('') 
                            ->items([
                                ...SuperadminCalonPenerimaResource::getNavigationItems(),
                                ...SuperadminKotakMBGResource::getNavigationItems(),
                                
                                // Menu custom manual
                                NavigationItem::make('Landing Page')
                                    ->url(fn () => route('welcome'))
                                    ->icon('heroicon-o-globe-alt'),
                                NavigationItem::make('Buku Panduan Superadmin')
                                    ->url(fn () => asset('panduan/BukuPanduan_Superadmin.pdf'))
                                    ->icon('heroicon-o-book-open') 
                                    ->sort(10)
                                    ->openUrlInNewTab(),
                            ]),
                    ]);
            })
            ->globalSearch(false)
            ->discoverResources(in: app_path('Filament/Resources/Superadmin'), for: 'App\Filament\Resources\Superadmin')
            ->discoverPages(in: app_path('Filament/Pages/Superadmin'), for: 'App\Filament\Pages\Superadmin')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Superadmin'), for: 'App\Filament\Widgets\Superadmin')
            ->widgets([
                SuperadminStatsOverview::class,
                
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