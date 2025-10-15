<?php

namespace Modules\BaseModule\Listeners;

class UpdateWorkflowListener
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
    public function handle(object $event): void
    {
        cacheWorkflowStatusesForRole(forceUpdate: true);
    }
}
