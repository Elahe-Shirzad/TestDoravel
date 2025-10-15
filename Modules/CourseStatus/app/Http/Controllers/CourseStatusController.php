<?php

namespace Modules\CourseStatus\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\CourseStatus\Generators\Tables\CourseStatusTable;
use Modules\CourseStatus\Http\Requests\CourseStatusStoreRequest;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\CourseStatus\Models\CourseStatusAccess;
use Illuminate\Support\Facades\Log;

class CourseStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        BladeLayout::table(CourseStatusTable::class);
        return view('coursestatus::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CourseStatus $courseStatus)
    {
        $isFirstStatus = !CourseStatus::checkIfStartStatusExists();

        $courseStatuses = prepareSelectComponentData(
            source: CourseStatus::available()
        );

        return view('coursestatus::create', compact('isFirstStatus', 'courseStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStatusStoreRequest $request)
    {
//
//        $data = array_merge(
//            $request->only(
//                'code',
//                    'name',
//                    'is_active',
//                    'is_start',
//                    'is_count',
//                    'is_publish',
//                    'is_end',
//                    'can_delete',
//                    'can_update',
//            ),
//           [ 'sort' => getNextSortValue(new CourseStatus())]
//        );
//
//        try {
//            $newCourseStatus = CourseStatus::create($data);
//
//            $transfer_status_access = $request->input('transfer_status_access');
//
//            if (!is_null($transfer_status_access)) {
//                foreach ($transfer_status_access as $Item) {
//                    CourseStatusAccess::create([
//                        'course_status_id' => $newCourseStatus->id,
//                        'child_course_status_id' => $Item,
//                    ]);
//                }
//            }
//
//            return backWithSuccess();
//
//        } catch (Exception $exception) {
//            Log::error($exception);
//            return backWithError();
//        }


        $courseStatuses = CourseStatus::available();
        $validated = $request->validated();



        $data = array_merge($validated, [
            'sort' => getNextSortValue(new CourseStatus()),
            'is_start' => $courseStatuses->count() > 0 ? $validated['is_start'] : BooleanState::YES->value,
            'is_end' => $validated['is_end'] ?? BooleanState::NO->value
        ]);

        unset($data['transfer_status_access']);

        DB::beginTransaction();
        try {
            // Note: Only one record must exist with active is_start
            if ($request->is_start == BooleanState::YES->value) {
                CourseStatus::changeTheActiveStatus('is_start');
            }

            $courseStatus = CourseStatus::create($data);

            if ($request->has('transfer_status_access')) {

                $courseStatusAccessData = CourseStatus::whereIn('id', $request->transfer_status_access)->get();
                $courseStatus->courseStatusAccesses()->attach($courseStatusAccessData);
            }

            DB::commit();
            routePropertyCollector()->forgetCachedDatabaseRoutes();
            systemStorage()->set('course', 'statuses', CourseStatus::available());

            return redirect()
                ->route('admin.system-settings.course-settings.course-statuses.index')
                ->withFlash(
                    message: __('basemodule::message.add_successfully'),
                    type: 'success',
                );
        } catch (Exception $exception) {
            DB::rollBack();
            Log::channel('course-status-module')->error($exception);
            return backWithError();
        }


    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('coursestatus::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('coursestatus::edit');
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
