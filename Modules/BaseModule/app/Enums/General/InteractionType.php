<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum InteractionType: string
{
    use EnumTools;

    case JUST_MODEL = 'model';
    case RELATED = 'related';
    case FAVORITE = 'favorite';
    case VIEW = 'view';
    case REACTION = 'reaction';
    case RESPONSE = 'response';
    case RATE = 'rate';
    case COMMENT = 'comment';
}
