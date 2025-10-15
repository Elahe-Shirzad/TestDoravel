<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum IsDefault: int
{
    use EnumTools;
    case YES = 1;
    case NO = 0;
}
