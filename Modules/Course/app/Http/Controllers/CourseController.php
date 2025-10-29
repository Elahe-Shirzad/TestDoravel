<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Bank\Enums\BooleanState;
use Modules\Bank\Enums\Files\FileType;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\BaseModule\Services\WorkFlowService;
use Modules\Course\Generators\Banners\CourseBanner;
use Modules\Course\Generators\Sections\CourseSection;
use Modules\Course\Generators\Tables\CourseTable;
use Modules\Course\Generators\Tabs\CourseTab;
use Modules\Course\Http\Requests\ChangeCourseStatusRequest;
use Modules\Course\Http\Requests\StoreCourseRequest;
use Modules\Course\Http\Requests\UpdateCourseRequest;
use Modules\Course\Http\Requests\UpdateCourseSeoRequest;
use Modules\Course\Http\Requests\UpdateCourseSettingRequest;
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
    public function show(Course $course)
    {
        BladeLayout::data([
            'course' => $course
        ]);
        BladeLayout::section(CourseSection::class);
        BladeLayout::banner(CourseBanner::class);

        return view('course::show',compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
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

        BladeLayout::data(['course'=> $course]);
        BladeLayout::banner(CourseBanner::class);
        BladeLayout::section(CourseSection::class);

        return view('course::edit',compact('course','courseCategories','courseLevels', 'instructors', 'imageFileType', 'videoFileType', 'coverFileType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        try {
            $data = array_merge($request->validated(), [
                'image' => $course->image_id,
                'cover' => $course->cover_image,
                'video' => $course->introduction_video_file_id
            ]);

            $course->update($data);

            uploadFile(
                module: 'Course',
                field: 'image',
                dbField: 'image_id',
                fileTypeCode: 'course_image',
                fileType: FileType::COURSEIMAGE,
                entity: $course
            );
            uploadFile(
                module: 'Course',
                field: 'cover',
                dbField: 'cover_image',
                fileTypeCode: 'course_cover_image',
                fileType: FileType::COURSECOVERIMAGE,
                entity: $course
            );
            uploadFile(
                module: 'Course',
                field: 'video',
                dbField: 'introduction_video_file_id',
                fileTypeCode: 'course_introduction_video',
                fileType: FileType::COURSEINTRODUCTIONVIDEO,
                entity: $course
            );

            return backWithSuccess(__('basemodule::message.update_successfully'));

        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError();
        }
    }


    public function seo(Course $course)
    {
        BladeLayout::data(['course'=> $course]);
        BladeLayout::section(CourseSection::class);
        BladeLayout::banner(CourseBanner::class);

        $seoRobots = prepareSelectComponentData(
            source: SeoRobot::class,
            moduleName: 'basemodule'
        );
        return view('course::seo',compact('course', 'seoRobots'));
    }


    public function seoUpdate(UpdateCourseSeoRequest $request, Course $course)
    {
        try {
            $course->update($request->validated());

            return backWithSuccess(__('basemodule::message.update_successfully'));

        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError();
        }
    }

    public function setting(Course $course)
    {
        BladeLayout::data(['course'=> $course]);
        BladeLayout::section(CourseSection::class);
        BladeLayout::banner(CourseBanner::class);

        return view('course::setting',compact('course'));
    }


    public function settingUpdate(UpdateCourseSettingRequest $request, Course $course)
    {
        try {
            $course->update($request->validated());
            return backWithSuccess(__('basemodule::message.update_successfully'));

        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    }


    public function changeStatus(ChangeCourseStatusRequest $request, Course $course)
    {
        if (!changeable($course?->course_status_id, 'course')) {
            backWithError(__("basemodule::message.not_permission"));
        }

        $courseStatus = CourseStatus::findOrFail($request->course_status_id);
        try {
            DB::beginTransaction();
            $course->update([
                'course_status_id' => $courseStatus->id
            ]);

//            CourseLog::create([
//                'admin_id' => auth(config('dornica-app.default_guard'))->id(),
//                'course_status_id' => $request->course_status_id,
//                'course_id' => $course->id,
//                'description' => $request->description,
//                'created_at' => now()
//            ]);
            DB::commit();

            return backWithSuccess(__("basemodule::message.change_status_successfully"));
        } catch (Exception $exception) {
            DB::rollBack();
            Log::channel('course-module')->error($exception);
            return backWithError();
        }
    }
}
