<?php

namespace Modules\BaseModule\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\BaseModule\Events\LoggedIn;
use Modules\BaseModule\Listeners\StoreStatusListener;
use Modules\BaseModule\Listeners\UpdateWorkflowListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        LoggedIn::class => [
            StoreStatusListener::class,
            UpdateWorkflowListener::class
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void
    {
    }
}
