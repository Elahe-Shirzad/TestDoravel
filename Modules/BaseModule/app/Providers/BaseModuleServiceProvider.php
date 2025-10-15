<?php

namespace Modules\BaseModule\Providers;

use Dornica\Foundation\Doravel\Facade\Doravel;
use Dornica\Foundation\RoutePropertyCollector\MenuCollector\Builders\MenuGroup;
use Dornica\Foundation\RoutePropertyCollector\MenuCollector\Builders\MenuSubgroup;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\BaseModule\Http\Controllers\Livewire\CommentChangeStatus;
use Modules\BaseModule\Http\Controllers\Livewire\CommentDetail;
use Modules\BaseModule\Http\Controllers\Livewire\Comments;
use Modules\BaseModule\Http\Controllers\Livewire\RelatedComment;
use Modules\BaseModule\View\Components\ChangeStatus;
use Modules\BaseModule\View\Components\ListChangeStatus;
use Modules\Blog\Models\Blog;
use Modules\Book\Models\Book;
use Modules\Teacher\Models\Teacher;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BaseModuleServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'BaseModule';

    protected string $nameLower = 'basemodule';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->registerMenuGroup();
        $this->registerComponents();

//        $this->registerCommands();
//        $this->registerCommandSchedules();
//        $this->registerConfig();
//        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower . '.' . $config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace') . '\\' . $this->name . '\\View\\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->nameLower)) {
                $paths[] = $path . '/modules/' . $this->nameLower;
            }
        }

        return $paths;
    }

    /**
     * @return void
     */
    public function registerMenuGroup(): void
    {
        // define menu groups
        Doravel::menu(function () {
            return [
                MenuGroup::make()
                    ->name('admin.teachers')
                    ->title(__('basemodule::section.teachers'))
                    ->icon('fa-regular fa-users')
                    ->subMenu([
                        MenuSubgroup::make()
                            ->name('admin.teachers.management')
                            ->title(__('basemodule::section.teachers_list'))
                            ->route('admin.teachers.management.index')
                            ->badge(
                                value: function () {
                                    return statusCounterInSideBar(
                                        model: Teacher::class,
                                        countAll: true
                                    );
                                },
                                style: 'light'
                            )
                    ]),
                MenuGroup::make()
                    ->name('admin.books')
                    ->title(__('basemodule::section.books'))
                    ->icon('fa-regular fa-books')
                    ->subMenu([
                        MenuSubgroup::make()
                            ->name('admin.books.list')
                            ->title(__('basemodule::section.books_list'))
                            ->route('admin.books.list.index')
                            ->badge(
                                value: function () {
                                    return statusCounterInSideBar(
                                        model: Book::class,
                                        columnName: 'book_status_id',
                                        countAll: true,
                                        section: 'book'
                                    );
                                },
                                style: 'light'
                            )
                    ]),
                MenuGroup::make()
                    ->name('admin.blogs')
                    ->title(__('basemodule::section.blogs'))
                    ->icon('fa-regular fa-grid-4')
                    ->subMenu([
                        MenuSubgroup::make()
                            ->name('admin.blogs.list')
                            ->title(__('basemodule::section.blogs_list'))
                            ->route('admin.blogs.list.index')
                            ->badge(
                                value: function () {
                                    return statusCounterInSideBar(
                                        model: Blog::class,
                                        columnName: 'blog_status_id',
                                        countAll: true,
                                        section: 'blog'
                                    );
                                },
                                style: 'light'
                            )
                    ]),
                MenuGroup::make()
                    ->name('admin.system-settings')
                    ->title(__('basemodule::section.settings'))
                    ->icon('fa-regular fa-gear')
                    ->subMenu([
                        MenuSubgroup::make()
                            ->name('admin.system-settings.teacher-settings')
                            ->title(__('basemodule::section.teacher_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.book-settings')
                            ->title(__('basemodule::section.book_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.blog-settings')
                            ->title(__('basemodule::section.blog_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.course-settings')
                            ->title(__('basemodule::section.course_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.subject-content-settings')
                            ->title(__('basemodule::section.subject_content_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.page-settings')
                            ->title(__('basemodule::section.page_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.link-settings')
                            ->title(__('basemodule::section.links_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.menu-settings')
                            ->title(__('basemodule::section.menu_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.ticket-settings')
                            ->title(__('basemodule::section.ticket_settings')),
                        MenuSubgroup::make()
                            ->name('admin.system-settings.settings')
                            ->title(__('basemodule::section.system_settings'))
                    ]),
                MenuGroup::make()
                    ->name('admin.tickets')
                    ->title(__('basemodule::section.tickets'))
                    ->icon('fa-regular fa-messages'),
                MenuGroup::make()
                    ->name('admin.slideshows')
                    ->title(__('basemodule::section.slideshows'))
                    ->icon('fa-regular fa-presentation'),
            ];
        });
    }

    /**
     * @return void
     */
    public function registerComponents(): void
    {
        // App Components
        Blade::component('change-status', ChangeStatus::class);

        // Livewire Components
        Livewire::component('comments', Comments::class);
        Livewire::component('related-comment', RelatedComment::class);
        Livewire::component('comment-detail', CommentDetail::class);
        Livewire::component('comment-change-status', CommentChangeStatus::class);
    }
}
