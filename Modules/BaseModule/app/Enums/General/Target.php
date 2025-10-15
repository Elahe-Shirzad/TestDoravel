<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum Target: int
{
    use EnumTools;

    case SELF = 1;
    case BLANK = 2;
    case PARENT = 3;
    case TOP = 4;
    case FRAME_NAME = 5;
}
