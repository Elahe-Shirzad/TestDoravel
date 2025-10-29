<?php

namespace Modules\Course\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\Course\Models\Course;

class CoursePolicy
{
    /**
     * @param Admin $admin
     * @param Course $course
     * @return Response
     */
    public function canUpdate(Admin $admin, Course $course): Response // NOSONAR: need $admin
    {
        if ($course->courseStatus->can_update == BooleanState::NO->value) {
            return Response::deny(__("basemodule::message.impossible_edit"));
        }

        return Response::allow();
    }

    /**
     * @param Admin $admin
     * @param Course $course
     * @return Response
     */
    public function canDelete(Admin $admin, Course $course): Response // NOSONAR: need $admin
    {
        if ($course->courseStatus->can_delete == BooleanState::NO->value) {
            return Response::deny(__("basemodule::message.impossible_delete"));
        }

        return Response::allow();
    }
}
