<?php

namespace Modules\Course\Generators\Filters;

use Dornica\PanelKit\Generator\Filter\BaseFilter;
use Dornica\PanelKit\Generator\Filter\Builders\Filter;
use Modules\Course\Models\CourseChapter;

class CourseChapterFilter extends BaseFilter
{
    /**
     * Define the filters for the panel.
     *
     * Each filter should be created using the Filter builder and returned in an array.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            Filter::make("course_chapter_id")
                ->placeholder(__('basemodule::field.id'))
                ->items(prepareSelectComponentData(
                        source: CourseChapter::where('course_id', $this->course->id)
                        ->dynamicAvailable(only_actives: false),
                    labelColumn: 'filter_title'
                ))
        ];
    }


    /**
     * @return array
     */
    public function defaults(): array
    {
        $request = request();
        $chapter = $this->chapter;

        return [
            'course_chapter_id' => $request->query('course_chapter_id_filter')
                ? decryptValue($request->query('course_id_filter'))
                : $chapter?->id
        ];
    }
}
