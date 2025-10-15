<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum SubjectContentWorkflowType: int
{
    use EnumTools;
    case ADMIN = 1;
    case TEACHER = 2;
    case BOTH = 3;
}
