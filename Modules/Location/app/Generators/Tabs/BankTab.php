<?php

namespace Modules\Location\Generators\Tabs;

use Dornica\PanelKit\Generator\Tab\BaseTab;

class BankTab extends BaseTab
{
    /**
     * Define the general route parameters for all tabs.
     *
     * If a tab does not have specific parameters, these will be used.
     *
     * @return array Route parameters.
     */
    public function routeParameters(): array
    {
        return [
            //
        ];
    }

    /**
     * Define the tabs and their configurations.
     *
     * Each tab should be created using the Tab builder.
     *
     * @return array List of tabs.
     */
    public function tabs(): array
    {
        return [
            //
        ];
    }
}
