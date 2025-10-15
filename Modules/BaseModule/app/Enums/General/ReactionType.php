<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum ReactionType: int
{
    use EnumTools;
    case LIKE = 1;
    case DISLIKE = 2;
}
