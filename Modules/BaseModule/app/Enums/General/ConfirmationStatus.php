<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum ConfirmationStatus: int
{
    use EnumTools;
    case PROCESSING = 0;
    case CONFIRMED = 1;
    case NOT_CONFIRMED = 2;
}
