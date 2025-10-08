<?php

namespace Modules\Bank\Enums;

use Dornica\Foundation\Core\Traits\EnumTools;

enum BankType: int
{
    use EnumTools;

    case Government = 1;
    case Private = 0;
}
