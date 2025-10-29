<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\Course\Generators\Banners\CourseBanner;
use Modules\Course\Generators\Filters\CourseChapterFilter;
use Modules\Course\Generators\Sections\CourseChapterSection;
use Modules\Course\Generators\Sections\CourseSection;
use Modules\Course\Generators\Tabs\CourseTab;
use Modules\Course\Http\Requests\SearchCourseChapterRequest;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseChapter;
use Modules\Course\Models\CourseContent;
use Exception;

class CourseChapterController extends Controller
{


    private function getFilters(): array
    {
        return [
            'course_chapter_id' => BladeLayout::filter()::value('course_chapter_id'),
        ];
    }

    /**
     * Apply filters to SubjectContent query
     */
    private function applyFilters($query, array $filters)
    {
        if (collect($filters)->filter()->isEmpty()) {
            return null;
        }

        return $query
            ->when($filters['course_chapter_id'], fn($q) => $q->where('id', $filters['course_chapter_id']))
            ->first();
    }


    public function search(SearchCourseChapterRequest $request)
    {
        try {
            $request->validated();

        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError();
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, CourseChapter $chapter)
    {
        BladeLayout::data([
            'course' => $course,
            'chapter' => $chapter
        ]);
        BladeLayout::banner(CourseBanner::class);
//        BladeLayout::tab(CourseTab::class);
        BladeLayout::section(CourseSection::class);

        $tableDataSource = CourseChapter::where('course_id', $course->id);
        $modifierClosure = function (array $row) use ($course): array {
            $row['has_content'] = CourseContent::where('course_chapter_id', $row['id'])
                ->where('course_id', $course->id)
                ->exists() ? BooleanState::YES->value : BooleanState::NO->value;

            $row['total_course_content'] = numberFormatter(CourseContent::query()
                ->where('course_id', $course->id)
                ->where('course_chapter_id', $row['id'])
                ->count());

            $row['id'] = encryptValue($row['id']);
            if($row['has_content'] == BooleanState::YES->value)
                $row['dependency'] = "به علت داشتن جلسات امکان حذف وجود ندارد.";
            else
                $row['dependency'] = "";

            $row['show_route'] = route('admin.courses.chapters.show', ['course' => encryptValue($course->id), 'chapter' => $row['id']]);
            $row['edit_route'] = route('admin.courses.chapters.edit', ['course' => encryptValue($course->id), 'chapter' => $row['id']]);
            $row['delete_route'] = route('admin.courses.chapters.destroy', ['course' => encryptValue($course->id), 'chapter' => $row['id']]);
//            $row['subject_content_route'] = route('admin.courses.chapters.contents.index', ['course' => encryptValue($course->id), 'chapter' => $row['id']]);
            return $row;
        };

        $isActiveData = prepareSelectComponentData(source: IsActive::class, moduleName: 'basemodule');

        return view('course::course-chapters.index', compact('course', 'chapter', 'tableDataSource', 'modifierClosure', 'isActiveData'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show(Course $course, CourseChapter $chapter)
    {
        BladeLayout::data([
            'course' => $course,
            'chapter' => $chapter
        ]);
        BladeLayout::banner(CourseBanner::class);
        BladeLayout::section(CourseChapterSection::class);
//        BladeLayout::tab(CourseTab::class);
        BladeLayout::filter(CourseChapterFilter::class);
        $filters = $this->getFilters();
        $content = $this->applyFilters(CourseChapter::query(), $filters);
        $chapter = $content ?? $chapter;


        return view('course::course-chapters.show', compact('course', 'chapter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('course::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
