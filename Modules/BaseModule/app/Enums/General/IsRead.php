<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum IsRead : int
{
    use EnumTools;
    case NO = 0;
    case YES = 1;
}
