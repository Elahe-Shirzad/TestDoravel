<?php

namespace Modules\Location\Generators\Banners;

use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\BladeComponents\UI\DropdownButton\DropdownButton;
use Dornica\BladeComponents\UI\DropdownButton\DropdownItem;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\Generator\Banner\BaseBanner;
use Dornica\PanelKit\Generator\Banner\Builders\Banner;
use Modules\Bank\Models\Location;
use Modules\BaseModule\Enums\General\ConfirmationStatus;
use Modules\Location\Enums\Service;

class LocationBanner extends BaseBanner
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
        return "شعبه".$this->location->branch."/ میدان ".$this->location->square;
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
                            ->label('شعبه')
                            ->value($this->location->branch)
                            ->icon("fa-solid fa-building-columns"),
                        Banner::make()
                            ->label("میدان")
                            ->value($this->location->square)
                            ->icon("fa-solid fa-building-columns"),
        ];
    }

    public function titleSuffixes(): array
    {


//        $service = $this->location->service;
//        $serviceLabel = match ($service) {
//            \Modules\Location\Enums\Service::ONLINE => __('location::enum.service.online'),
//            \Modules\Location\Enums\Service::OFFLINE => __('location::enum.service.offline'),
//            default => '-',
//        };

        $service=$this->location->service;
        $serviceColor=stateBadgeVariant($service->value);
        $serviceEnumName=getEnumName(Service::class, $service, 'location');

        return [
            Badge::make()
                ->value($serviceEnumName)
                ->variant($serviceColor)
                ->appearance('light')
                ->tooltip(__('location::general.service')),

            Badge::make()
                ->value("شعبه".$this->location->branch)
                ->customColor($this->location->color)
                ->tooltip(__('location::general.branch'))
        ];
    }


    public function actionButtons(): array
    {
//        $disabled = false;
        $disabledTooltip = '';
//        if ($this->blog_category->hasDependencies()) {
//            $disabled = true;
//            $disabledTooltip = $this->blog_category->getDeleteErrorMessage();
//        }
//
//        $frontUrlConfig = config('project.front_base_url');
//        $frontUrl = $frontUrlConfig;
//        $slug = (string)$this->blog_category->slug;
        $id = (string)$this->location->id;

        return [
            DropdownButton::make()
                ->menuPosition('right')
                ->title(__('location::general.actions'))
                ->icon('fa-regular fa-ellipsis-vertical')
                ->closeOnClick(false)
                ->variant('primary')
                ->appearance('light')
                ->slot([
                    DropdownItem::make()
                        ->title(__('location::general.view'))
//                        ->href($frontUrl . '/blog-category/' . $slug)
                        ->icon('fa-regular fa-file'),

                    DropdownItem::make()
                        ->title(__('location::general.copy_link'))
                        ->elementClass('copy-link')
                        ->icon('fa-regular fa-copy'),
//
                    DropdownItem::make()
                        ->title(__('location::general.destroy'))
                        ->icon('fa-regular fa-trash-can')
                        ->confirmation(true)
                        ->confirmationType('danger')
                        ->confirmationMessage(__('location::general.delete_confirmation_message'))
                        ->confirmationIcon('fa-regular fa-trash-can')
                        ->confirmButtonText(__('location::general.destroy'))
//                        ->disabled($disabled)
                        ->variant('danger')
//                        ->disabledTooltip($disabledTooltip)
                        ->href(route('admin.base-information.locations.destroy',
                            encryptValue($id)))
                        ->method('delete'),
                ])
        ];
    }
}
