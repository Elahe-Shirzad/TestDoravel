<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum ContentStatus : string
{
    use EnumTools;

    case PUBLISHED = 'published';
    case EXPIRED = 'expired';
}
