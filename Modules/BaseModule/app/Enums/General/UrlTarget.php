<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum UrlTarget: int
{
    use EnumTools;

    case SELF = 1;
    case BLANK = 2;
}
