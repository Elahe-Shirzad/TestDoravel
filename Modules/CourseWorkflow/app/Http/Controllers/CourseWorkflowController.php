<?php

namespace Modules\CourseWorkflow\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\BaseModule\Enums\General\WorkFlowType;
use Modules\BaseModule\Services\WorkFlowService;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\CourseWorkflow\Generators\Tables\CourseWorkflowTable;
use Modules\CourseWorkflow\Http\Requests\StoreCourseWorkflowRequest;
use Modules\CourseWorkflow\Http\Requests\UpdateCourseWorkflowRequest;
use Modules\CourseWorkflow\Models\CourseWorkflow;
use Modules\CourseWorkflow\Models\CourseWorkflowCourseStatus;

class CourseWorkflowController extends Controller
{
    /**
     * @var WorkFlowService
     */
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
        BladeLayout::table(CourseWorkflowTable::class);

        return view('courseworkflow::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // get available roles
            $roles = prepareSelectComponentData(Role::getAvailableData());
            // get available course statuses
            $courseStatuses = prepareSelectComponentData(CourseStatus::getAvailableData());

        } catch (Exception $exception) {
            // error handle and log
            Log::error($exception);
            return backWithError();
        }
        return view('courseworkflow::create', compact('roles', 'courseStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseWorkflowRequest $request) {
        try {
            // validate request
            $validatedRequest = $request->validated();

            // start transaction
            DB::beginTransaction();

            // create workflow
            $workflow = CourseWorkflow::create($validatedRequest);

            // assign statuses to workflow
            CourseWorkflowCourseStatus::bulkAssignStatuses($workflow->id, $request->input('statuses_to_view', []), WorkflowType::VIEW);
            CourseWorkflowCourseStatus::bulkAssignStatuses($workflow->id, $request->input('statuses_to_change', []), WorkflowType::CHANGE);
            CourseWorkflowCourseStatus::bulkAssignStatuses($workflow->id, $request->input('statuses_to_set', []), WorkflowType::SET);

            // clear cache of workflow
            $this->clearCatch();

            // commit changes on database
            DB::commit();

            // redirect
            return redirect(route("admin.system-settings.course-settings.course-workflows.index"))->withFlash(
                message: __("basemodule::message.add_successfully"),
                type: "success",
                title: __("basemodule::field.success")
            );
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return backWithError();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseWorkflow $courseWorkflow)
    {
        try {
            // send data to edit blade
            BladeLayout::data([
                'role' => $courseWorkflow->role,
                'course_workflow' => $courseWorkflow
            ]);

            // get roles wuth used item
            $roles = Role::getAvailableData(selectedSelfItem: $courseWorkflow->role_id);

            // init workflow service
            $this->workflowService->workflowId = $courseWorkflow->id;

            // get field data
            $userViewStatusIds = $this->workflowService->getViewWorkFlowStatusIds();
            $userChangeStatusIds = $this->workflowService->getChangeWorkFlowStatusIds();
            $userSetStatusIds = $this->workflowService->getSetWorkFlowStatusIds();



            // get available data on course status
            $viewStatues = CourseStatus::getAvailableData(selectedSelfItem: $userViewStatusIds);
            $changeStatues = CourseStatus::getAvailableData(selectedSelfItem: $userChangeStatusIds);
            $setStatuses = CourseStatus::getAvailableData(selectedSelfItem: $userSetStatusIds);

            // build select component data
            $roles = prepareSelectComponentData($roles);
            $viewStatues = prepareSelectComponentData($viewStatues);
            $changeStatues = prepareSelectComponentData($changeStatues);
            $setStatuses = prepareSelectComponentData($setStatuses);

            // go on view
            return view('courseworkflow::edit', compact(
                'courseWorkflow',
                'roles',
                'viewStatues',
                'changeStatues',
                'setStatuses',
                'userViewStatusIds',
                'userChangeStatusIds',
                'userSetStatusIds'
            ));
        } catch (Exception $exception) {
            Log::error($exception);
            return backWithError();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseWorkflowRequest $request, CourseWorkflow $courseWorkflow) {
        try {
            // validate request
            $validated = $request->validated();

            // init workflow service
            $this->workflowService->workflowId = $courseWorkflow->id;

            $workflowTypes = [
                'view' => [
                    'old' => $this->workflowService->getViewWorkFlowStatusIds(),
                    'new' => $validated['statuses_to_view'] ?? [],
                    'type' => WorkFlowType::VIEW->value,
                ],
                'change' => [
                    'old' => $this->workflowService->getChangeWorkFlowStatusIds(),
                    'new' => $validated['statuses_to_change'] ?? [],
                    'type' => WorkflowType::CHANGE->value,
                ],
                'set' => [
                    'old' => $this->workflowService->getSetWorkFlowStatusIds(),
                    'new' => $validated['statuses_to_set'] ?? [],
                    'type' => WorkflowType::SET->value,
                ]
            ];

            // init transaction
            DB::beginTransaction();
            // update workflow
            $courseWorkflow->update($validated);

            // loop on workflow type and remove item's
            foreach ($workflowTypes as $key => $workflow) {
                // get intersect items
                $intersect = array_intersect($workflow['old'], $workflow['new']);

                // remover item's
                $toRemove = array_diff($workflow['old'], $intersect);

                // register new statuses
                $toRegister = array_diff($workflow['new'], $intersect);

                // delete removed statuses
                if (!empty($toRemove)) {
                    // delete statuses
                    $courseWorkflow->
                    courseWorkflowCourseStatuses()->
                    whereIn('course_status_id', $toRemove)->
                    where('type', $workflow['type'])->
                    delete();
                }

                // Bulk insert new teacher statuses
                CourseWorkflowCourseStatus::bulkAssignStatuses(
                    $courseWorkflow->id,
                    $toRegister,
                    WorkFlowType::from($workflow['type']),
                );
            }

            // remove cache of course workflow
            $this->clearCatch();

            // commit changes on database
            DB::commit();

            // redirect to route
            return redirect(route("admin.system-settings.course-settings.course-workflows.index"))->
            withFlash(
                message: __("basemodule::message.update_successfully"),
                type: "success",
                title: __("basemodule::general.success")
            );
        } catch (Exception $exception) {
            // roll back transaction
            DB::rollBack();
            // log
            Log::error($exception);
            return backWithError();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseWorkflow $courseWorkflow) {
        try {
            // init transaction
            DB::beginTransaction();
            // delete item from database
            $courseWorkflow->delete();
            // clear cache
            $this->clearCatch();
            routePropertyCollector()->forgetCachedDatabaseRoutes();
            // commit changes on database
            DB::commit();
            // go back with message
            return back()->withFlash(
                message: __('basemodule::message.delete_successfully'),
                type: 'success'
            );
        } catch (Exception $exception) {
            // handle error and log
            DB::rollBack();
            Log::channel('course-workflow-module')->error($exception);
            return backWithError();
        }
    }

    /**
     * @return void
     */
    private function clearCatch()
    {
        removeWorkflowCacheBySection('course');
        cacheWorkflowStatusesForRole(true);
    }
}
