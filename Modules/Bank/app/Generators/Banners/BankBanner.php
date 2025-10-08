<?php

namespace Modules\Bank\Generators\Banners;

use Dornica\PanelKit\Generator\Banner\BaseBanner;
use Dornica\PanelKit\Generator\Banner\Builders\Banner;

class BankBanner extends BaseBanner
{
    /**
     * title of banner
     *
     * use any logics to provide a title for showing banner in the blade
     *
     * @return null|string
     */
//    public function title(): ?string
//    {
//        return null;
//    }

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
                ->label('بانک')
                ->value($this->bank->name)
                ->icon("fa-solid fa-building-columns"),
            Banner::make()
                ->label("کد بانک")
                ->value($this->bank->code)
                ->icon("fa-solid fa-building-columns"),
        ];
    }
}
