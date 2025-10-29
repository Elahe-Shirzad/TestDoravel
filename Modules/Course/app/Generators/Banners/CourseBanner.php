<?php

namespace Modules\Course\Generators\Banners;

use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\BladeComponents\UI\Button\Button;
use Dornica\PanelKit\Generator\Banner\BaseBanner;
use Dornica\PanelKit\Generator\Banner\Builders\Banner;
use Modules\Location\Enums\Service;

class CourseBanner extends BaseBanner
{


    public function __construct()
    {
        $this->isSticky();
    }
    /**
     * title of banner
     *
     * use any logics to provide a title for showing banner in the blade
     *
     * @return null|string
     */
    public function title(): ?string
    {
        return $this->course->title;
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
            Banner::make()
                ->label(__('basemodule::field.category'))
                ->value($this->course->courseCategory->title ?? "-")
                ->icon("fa-regular fa-grid-2"),

            Banner::make()
                ->label(__('course::field.level'))
                ->value($this->course->courseLevel->title ?? "-")
                ->icon("fa-regular fa-grid-2"),

            Banner::make()
                ->label("مدرس دوره")
                ->value($this->course->instructor->first_name . ' ' . $this->course->instructor->last_name ?? "-")
                ->icon("fa-regular fa-user"),
        ];
    }



    public function titleSuffixes(): array
    {

        $course=$this->course;
        $courseStatus=$course->courseStatus;

        return [
            Badge::make()
                ->value($course->id)
                ->variant('info-2')
                ->appearance('light')
                ->tooltip(__('location::general.service')),

            Badge::make()
                ->value($courseStatus->name)
                ->customColor($courseStatus->color)
                ->tooltip(__('location::general.branch'))
        ];
    }


    public function actionButtons(): array
    {
        $buttons = [];

        if (changeable($this->course->course_status_id, 'course')) {
            $buttons[] = Button::make()
                ->title(__('basemodule::operation.change_status'))
                ->extraAttributes("data-bs-target=#change-status data-bs-toggle=modal");
        }

        return $buttons;
    }
}
