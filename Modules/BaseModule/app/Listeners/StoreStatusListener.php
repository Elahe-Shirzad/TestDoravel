<?php

namespace Modules\BaseModule\Listeners;

use Modules\BaseModule\Events\LoggedIn;
use Modules\BookStatus\Models\BookStatus;
use Modules\TeacherStatus\Models\TeacherStatus;

class StoreStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoggedIn $event): void
    {
        collect([
            'teacher' => TeacherStatus::class,
            'book' => BookStatus::class
        ])->each(fn($class, $key) => systemStorage()->set($key, 'statuses', $class::available()));
    }
}
