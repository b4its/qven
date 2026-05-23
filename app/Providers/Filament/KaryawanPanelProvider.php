<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Karyawan\KaryawanKotakMBGS\KaryawanKotakMBGResource;
use App\Filament\Widgets\Karyawan\KaryawanStatsOverview;
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

class KaryawanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandNames = 'Karyawan Panel';
        $logoPath = asset('assets/logo/origin_oryphem_black.png');
        return $panel
            ->id('karyawan')
            ->path('karyawan')
            ->colors([
                'primary' => Color::Cyan,
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
            ->tenant(Vendor::class, slugAttribute: 'id') // URL akan menjadi /admin/1/dashboard, /admin/2/dst
            ->tenantMenu(true)
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url('#edit-profile'),
            ])

            ->renderHook(
                'panels::body.start',
                fn (): string => Blade::render('
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            if (navigator.geolocation) {
                                console.log("Meminta lokasi..."); // Cek apakah baris ini jalan di console
                                navigator.geolocation.getCurrentPosition(
                                    function(position) {
                                        fetch("/save-coordinate", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                            },
                                            body: JSON.stringify({
                                                lat: position.coords.latitude,
                                                lng: position.coords.longitude
                                            })
                                        }).then(res => console.log("Koordinat tersimpan"));
                                    }, 
                                    function(error) {
                                        console.error("Error Geolocation: ", error.message);
                                    }, 
                                    { enableHighAccuracy: true }
                                );
                            } else {
                                console.error("Browser tidak mendukung Geolocation");
                            }
                        });
                    </script>
                ')
            )

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
                        ...KaryawanKotakMBGResource::getNavigationItems(),

                        NavigationItem::make('Landing Page')
                                    ->url(fn () => route('welcome'))
                                    ->icon('heroicon-o-globe-alt'),

                        NavigationItem::make('Buku Panduan Karyawan')
                                    ->url(fn () => asset('panduan/BukuPanduan_Karyawan.pdf'))
                                    ->icon('heroicon-o-book-open') 
                                    ->sort(10)
                                    ->openUrlInNewTab(),
                    ]);
            })
            ->globalSearch(false)
            ->discoverResources(in: app_path('Filament/Resources/Karyawan'), for: 'App\Filament\Resources\Karyawan')
            ->discoverPages(in: app_path('Filament/Pages/Karyawan'), for: 'App\Filament\Pages\Karyawan')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Karyawan'), for: 'App\Filament\Widgets\Karyawan')
            ->widgets([
                KaryawanStatsOverview::class,
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
