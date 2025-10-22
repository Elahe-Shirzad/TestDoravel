<?php

use Modules\CourseWorkflow\Models\CourseWorkflow;
use Modules\CourseWorkflow\Models\CourseWorkflowCourseStatus;


return [
    'course' => [
        'modelClass' => CourseWorkflowCourseStatus::class,
        'workflowClass' => CourseWorkflow::class,
        'workflowRelation' => "courseContentWorkflows",
        'workflowIdColumnName' => "course_workflow_id",
        'statusIdColumnName' => "course_status_id"
    ],
];

