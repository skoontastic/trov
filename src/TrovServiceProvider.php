<?php

namespace Trov;

use App\Models\User;
use Trov\Models\Page;
use Livewire\Livewire;
use Trov\Models\Media;
use Filament\Facades\Filament;
use Trov\Observers\PageObserver;
use Trov\Observers\UserObserver;
use Trov\Observers\MediaObserver;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\File;
use Filament\Navigation\UserMenuItem;
use Spatie\LaravelPackageTools\Package;
use Trov\Commands\RegenerateThumbnails;

class TrovServiceProvider extends PluginServiceProvider
{
    protected array $resources = [];

    protected array $beforeCoreScripts = [
        'https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('trov')
            ->hasConfigFile(['trov', 'filament', 'filament-breezy', 'filament-forms-tinyeditor', 'filament-shield'])
            ->hasAssets()
            ->hasViews()
            ->hasRoute("web")
            ->hasCommand($this->getCommands())
            ->hasMigrations([
                'create_trov_tables',
            ]);
    }

    public function register()
    {
        parent::register();

        require_once __DIR__ . '/Helpers.php';
    }

    public function boot()
    {
        parent::boot();

        Filament::serving(function () {
            Filament::registerTheme(asset('vendor/trov/trov.css'));
        });

        Livewire::component('media-picker', Forms\Components\MediaPicker::class);
        Livewire::component('create-media-form', Forms\Components\CreateMediaForm::class);
        Livewire::component('pages-overview', Widgets\PagesOverview::class);
        Livewire::component('posts-overview', Widgets\PostsOverview::class);
        Livewire::component('faqs-overview', Widgets\FaqsOverview::class);
        Livewire::component('framework-overview', Widgets\FrameworkOverview::class);

        User::observe(UserObserver::class);
        Media::observe(MediaObserver::class);
        Page::observe(PageObserver::class);
    }

    protected function getResources(): array
    {
        $core = [
            Resources\UserResource::class,
            Resources\MediaResource::class,
            Resources\PageResource::class,
        ];

        $features = [];

        foreach (config('trov.features') as $feature => $data) {
            if ($data['active'] && $data['resources']) {
                foreach ($data['resources'] as $class) {
                    $features[] = $class;
                }
            }
        }

        return array_merge($core, $features);
    }

    protected function getCommands(): array
    {
        return [
            RegenerateThumbnails::class,
        ];
    }
}
