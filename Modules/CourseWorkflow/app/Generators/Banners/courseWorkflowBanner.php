<?php

namespace Modules\CourseWorkflow\Generators\Banners;

use Dornica\PanelKit\Generator\Banner\BaseBanner;

class courseWorkflowBanner extends BaseBanner
{
    /**
     * title of banner
     *
     * use any logics to provide a title for showing banner in the blade
     *
     * @return null|string
     */
    public function title(): ?string
    {
        return null;
    }

    /**
     * banner items to show
     *
     * Define each item for the banner inside the returning array by using banner builder
     *
     * @return array
     */
    public function items(): array
    {
        return [
            //
        ];
    }
}
