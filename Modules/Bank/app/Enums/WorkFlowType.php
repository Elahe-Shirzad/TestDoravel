<?php

namespace Modules\Bank\Enums;

use Dornica\Foundation\Core\Traits\EnumTools;

enum WorkFlowType: int
{
    use EnumTools;
    case VIEW = 1;
    case CHANGE = 2;
    case SET = 3;
}
