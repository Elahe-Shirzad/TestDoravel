<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum CommentStatus: int
{
    use EnumTools;

    case SEND_COMMENT_WITH_CONFIRM = 1;
    case SEND_COMMENT_WITHOUT_CONFIRM = 2;
    case DISABLE = 0;
}
