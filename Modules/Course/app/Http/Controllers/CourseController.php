<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Bank\Enums\BooleanState;
use Modules\Bank\Enums\Files\FileType;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\BaseModule\Services\WorkFlowService;
use Modules\Course\Generators\Tables\CourseTable;
use Modules\Course\Http\Requests\StoreCourseRequest;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseLog;
use Modules\CourseCategory\Models\CourseCategory;
use Modules\CourseLevel\Models\CourseLevel;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\Instructor\Models\Instructor;
use Exception;

class CourseController extends Controller
{

    private WorkFlowService $workflowService;

    public function __construct()
    {
        $this->workflowService = new WorkFlowService(config('workflow')['course']);

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        BladeLayout::table(CourseTable::class);
        $this->workflowService->viewable(BladeLayout::table()->getQueryBuilder());
        return view('course::index');
    }

    public function byStatus()
    {
        $statusCode = getCurrentRouteStatusCode();

        $courseStatus = CourseStatus::firstWhere("code", $statusCode);

        abort_if(!$statusCode || !$courseStatus, 404);

        BladeLayout::table(CourseTable::class);
        $this->workflowService->viewable(BladeLayout::table()->getQueryBuilder()->where('course_status_id', $courseStatus->id));
        return view('course::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseCategories = prepareSelectComponentData(
            source: CourseCategory::available(),
            labelColumn: 'title'
        );

        $courseLevels = prepareSelectComponentData(
            source: CourseLevel::available(),
            labelColumn: 'title'
        );

        $instructors = prepareSelectComponentData(
            source: Instructor::available(),
            labelColumn: 'full_name'
        );
        $seoRobots = prepareSelectComponentData(
            source: SeoRobot::class,
            moduleName: 'basemodule'
        );


        $imageFileTypeInfo = getFileType(FileType::COURSEIMAGE, 'course_image');
        $imageFileType = getUploadRequirements($imageFileTypeInfo);

        $videoFileTypeInfo = getFileType(FileType::COURSEINTRODUCTIONVIDEO, 'course_introduction_video');
        $videoFileType = getUploadRequirements($videoFileTypeInfo);

        $coverFileTypeInfo = getFileType(FileType::COURSECOVERIMAGE, 'course_cover_image');
        $coverFileType = getUploadRequirements($coverFileTypeInfo);

        return view('course::create',
            compact('courseCategories', 'courseLevels', 'instructors', 'imageFileType', 'videoFileType', 'coverFileType','seoRobots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $courseStatusStarter = CourseStatus::where('is_start', '=', BooleanState::YES)->first();
            if (!$courseStatusStarter) {
                return back()->withFlash(
                    message: __("basemodule::message.no_set_start_status"),
                    type: "error",
                    title: __("basemodule::field.error")
                );
            }

            $validated = array_merge($request->validated(), [
                'sort' => getNextSortValue(Course::class),
                'course_status_id' => $courseStatusStarter->id,
                'total_duration' => 0,
                'image' => null,
                'cover' => null,
                'video' => null,
            ]);


            $newCourse = Course::create($validated);

//            CourseLog::create([
//                'admin_id' => auth(config('dornica-app.default_guard'))->id(),
//                'course_status_id' => $newCourse->course_status_id,
//                'course_id' => $newCourse->id,
//                'created_at' => now()
//            ]);



            uploadFile(
                module: 'Course',
                field: 'image',
                dbField: 'image_id',
                fileTypeCode: 'course_image',
                fileType: FileType::COURSEIMAGE,
                entity: $newCourse
            );

            uploadFile(
                module: 'Course',
                field: 'cover',
                dbField: 'cover_image',
                fileTypeCode: 'course_cover_image',
                fileType: FileType::COURSECOVERIMAGE,
                entity: $newCourse
            );

            uploadFile(
                module: 'Course',
                field: 'video',
                dbField: 'introduction_video_file_id',
                fileTypeCode: 'course_introduction_video',
                fileType: FileType::COURSEINTRODUCTIONVIDEO,
                entity: $newCourse
            );

            return redirect()
                ->route('admin.courses.management.index')
                ->withFlash(
                    message: __("basemodule::message.add_successfully"),
                    type: "success",
                    title: __("basemodule::field.success")
                );
        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError();
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('course::show');
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
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    }
}
