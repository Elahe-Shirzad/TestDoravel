<?php

namespace Modules\Location\Enums;

use Dornica\Foundation\Core\Traits\EnumTools;

enum Service: int
{
    use EnumTools;

    case OFFLINE = 1;
    case ONLINE = 0;
}
