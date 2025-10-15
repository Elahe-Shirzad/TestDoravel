<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum LinkType: int
{
    use EnumTools;

    case INTERNAL = 1;
    case EXTERNAL = 2;
}
