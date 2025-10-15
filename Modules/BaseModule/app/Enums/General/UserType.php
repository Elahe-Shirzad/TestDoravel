<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum UserType: int
{
    use EnumTools;

    case ALL = 0;
    case USERS = 1;
}
