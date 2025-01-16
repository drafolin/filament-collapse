<?php

namespace Drafolin\FilamentCollapse;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCollapseServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-collapse';

    public static string $viewNamespace = 'filament-collapse';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('filament-collapse', __DIR__ . '/../resources/dist/filament-collapse.css'),
        ], 'drafolin/filament-collapse');
    }
}
