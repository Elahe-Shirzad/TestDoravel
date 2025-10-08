<?php

namespace Modules\Bank\Enums;

use Dornica\Foundation\Core\Traits\EnumTools;

enum BooleanState : int
{
    use EnumTools;
    case YES = 1;
    case NO = 0;
}
