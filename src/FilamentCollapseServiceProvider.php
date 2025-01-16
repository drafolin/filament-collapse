<?php

namespace Drafolin\FilamentCollapse;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Drafolin\FilamentCollapse\Commands\FilamentCollapseCommand;
use Drafolin\FilamentCollapse\Testing\TestsFilamentCollapse;

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
            Css::make('filament-collapse', __DIR__ . '/../resources/dist/filament-collapse.css')->loadedOnRequest(),
        ], 'drafolin/filament-collapse');
    }
}
