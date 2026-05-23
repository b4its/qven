<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\PenerimaBuktiKotakDiterimaResource;
use App\Filament\Widgets\Penerima\PenerimaStatsOverview;
use App\Models\InstansiPenerima;
use App\Models\Vendor;
use Blade;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PenerimaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandNames = 'Penerima Panel';
        $logoPath = asset('assets/logo/origin_oryphem_black.png');
        return $panel
            ->id('penerima')
            ->path('penerima')
            ->brandName($brandNames)
            ->brandLogo(fn() => view('filament.components.brand-logo', [
                'logoPath' => $logoPath, 
                'brandNames' => $brandNames, 
                'instansiName' => Auth::user()?->instansiPenerima ? "- " . Auth::user()->instansiPenerima->name : "- "
            ]))
            ->login()
            ->colors([
                'primary' => Color::Emerald,
                'royal'     => '#4f46e5', // Biru keunguan mewah
                'emerald'   => '#10b981', // Hijau perhiasan
                'ocean'     => '#0ea5e9', // Biru laut cerah
                'sunshine'  => '#f59e0b', // Kuning hangat
                'crimson'   => '#e11d48', // Merah gelap elegan
                'slate'     => '#475569', // Abu-abu kebiruan profesional
                'night'     => '#1e293b', // Gelap pekat
                'cyan'  => '#3fbde4', // Ungu modern
            ])
            ->tenant(InstansiPenerima::class, slugAttribute: 'id') // URL akan menjadi /admin/1/dashboard, /admin/2/dst
            ->tenantMenu(false)
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
                        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        // 1. Dashboard selalu di atas
                        ...Dashboard::getNavigationItems(),
                        ...PenerimaBuktiKotakDiterimaResource::getNavigationItems(),

                        NavigationItem::make('Landing Page')
                                    ->url(fn () => route('welcome'))
                                    ->icon('heroicon-o-globe-alt'),

                        NavigationItem::make('Buku Panduan Penerima')
                                    ->url(fn () => asset('panduan/BukuPanduan_Penerima.pdf'))
                                    ->icon('heroicon-o-book-open') 
                                    ->sort(10)
                                    ->openUrlInNewTab(),
                    ]);
            })
            ->globalSearch(false)
            ->discoverResources(in: app_path('Filament/Resources/Penerima'), for: 'App\Filament\Resources\Penerima')
            ->discoverPages(in: app_path('Filament/Pages/Penerima'), for: 'App\Filament\Pages\Penerima')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Penerima'), for: 'App\Filament\Widgets\Penerima')
            ->widgets([
                PenerimaStatsOverview::class,
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
