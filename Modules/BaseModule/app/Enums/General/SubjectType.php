<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum SubjectType: int
{
    use EnumTools;
    case LESSON = 1;
    case TOPIC = 2;
    case SESSION = 3;
    case STEP = 4;
}
