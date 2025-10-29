<?php

namespace Modules\Course\Generators\Sections;

use Dornica\PanelKit\Generator\Section\BaseSection;
use Dornica\PanelKit\Generator\Section\Builders\Section;
use Modules\CourseWorkflow\Generators\Tables\courseWorkflowTable;

class CourseSection extends BaseSection
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
        return __('course::section.course_management');
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
            'course' => encryptValue($this->course->id)
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
            Section::make('')
                ->routeName('admin.courses.show')
                ->label('جزئیات')
                ->tab('tab_bank'),

            Section::make('bank-section_edit-id')
                ->routeName('admin.courses.edit')
                ->label('ویرایش')
                ->disabled(checkPolicy('canUpdate', $this->course)->allowed() === false)
                ->disabledTooltip(checkPolicy('canUpdate', $this->course)->message())
            ,

            Section::make('bank-section_location-id')
                ->routeName('admin.courses.seo.edit')
                ->label('تنظیمات سئو')
                ->disabled(checkPolicy('canUpdate', $this->course)->allowed() === false)
                ->disabledTooltip(checkPolicy('canUpdate', $this->course)->message())
            ,

            Section::make('bank-section_location-id')
                ->routeName('admin.courses.settings.edit')
                ->label('تنظیمات کلی')
                ->disabled(checkPolicy('canUpdate', $this->course)->allowed() === false)
                ->disabledTooltip(checkPolicy('canUpdate', $this->course)->message())
            ,

            Section::make('bank-section_location-id')
                ->routeName('admin.courses.chapters.index')
                ->label('فصل ها')
                ->disabled(checkPolicy('canUpdate', $this->course)->allowed() === false)
                ->disabledTooltip(checkPolicy('canUpdate', $this->course)->message())
            ,
        ];
    }
}
