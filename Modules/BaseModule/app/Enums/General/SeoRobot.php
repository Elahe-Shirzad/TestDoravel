<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum SeoRobot: int
{
    use EnumTools;
    case INDEX_FOLLOW = 1;
    case INDEX_NOFOLLOW = 2;
    case NOINDEX_FOLLOW = 3;
    case NOINDEX_NOFOLLOW = 4;
}
