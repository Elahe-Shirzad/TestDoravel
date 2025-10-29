<?php

namespace Modules\Course\Generators\Sections;

use Dornica\PanelKit\Generator\Section\BaseSection;
use Dornica\PanelKit\Generator\Section\Builders\Section;

class CourseChapterSection extends BaseSection
{
     /**
     * Get the default tab for the section.
     *
     * If no specific tab is set for a section, this tab will be used.
     *
     * @return string Default tab name.
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
            'course' => encryptValue($this->course->id),
            'chapter' => encryptValue($this->chapter->id)
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
            Section::make('show')
                ->routeName('admin.courses.chapters.show')
                ->label(__("basemodule::operation.show")),

            Section::make('edit')
                ->routeName('admin.courses.chapters.edit')
                ->label(__("basemodule::operation.edit.general")),

//            Section::make('show')
//                ->routeName('admin.courses.chapters.contents.index')
//                ->label(__("basemodule::field.sessions"))
//                ->childRoutes([
//                    'admin.courses.chapters.contents.create'
//                ]),
        ];
    }
}
