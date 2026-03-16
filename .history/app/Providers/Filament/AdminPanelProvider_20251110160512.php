public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->colors([
            'primary' => Color::Amber,
        ])
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->pages([
            Dashboard::class,
        ])
        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
        ->widgets([
            AccountWidget::class,
            FilamentInfoWidget::class,
        ])
        ->globalSearch(false)
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
        ->plugins([
            FilamentShieldPlugin::make(),
        ])
        ->authMiddleware([
            Authenticate::class,
        ])
        // ✅ NAVIGATION GROUPS - ATUR DI SINI SAJA
        ->navigationGroups([
            'Data Master',
            'System Management',
            'Settings',
        ])
        // ✅ ATUR MANUAL JIKA PERLU
        ->resources([
            \App\Filament\Resources\Santris\SantriResource::class,
        ]);
}