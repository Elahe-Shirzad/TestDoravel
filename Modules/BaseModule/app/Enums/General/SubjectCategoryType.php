<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum SubjectCategoryType: int
{
    use EnumTools;
    case LIST = 1;
    case GENERALITY = 2;
    case TEACHING_METHOD = 3;
}
