<?php

namespace Modules\Bank\Generators\Sections;

use Dornica\PanelKit\Generator\Section\BaseSection;
use Dornica\PanelKit\Generator\Section\Builders\Section;

class BankSection extends BaseSection
{
     /**
     * Get the default tab for the section.
     *
     * If no specific tab is set for a section, this tab will be used.
     *
     * @return string Default tab name.
     */
    public function defaultTab(): string
    {
        return "";
    }

     /**
     * Get the title of the section.
     *
     * The title is used as the display name of the section in the panel.
     *
     * @return string Section title.
     */
    public function title(): string
    {
        return "";
    }

     /**
     * Define the general route parameters for all section links.
     *
     * This can be used to pass shared parameters to all section routes.
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
     * Define the sections and their configurations.
     *
     * Each section should be created using the Section builder.
     *
     * @return array List of sections.
     */
    public function sections(): array
    {
        return [
            Section::make('bank-section_show-id')
                ->routeName('admin.base-information.banks.show')
                ->label('جزئیات'),

            Section::make('bank-section_edit-id')
                ->routeName('admin.base-information.banks.edit')
                ->label('ویرایش'),

            Section::make('bank-section_location-id')
                ->routeName('admin.base-information.banks.location')
                ->label('لیست شعبات'),
        ];
    }
}
