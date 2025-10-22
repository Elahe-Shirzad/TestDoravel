<?php

namespace Modules\Bank\Enums\Files;

enum FileType : int
{
    case GENERAL = 0;
    case BANK = 1 ;
    case LOCATION = 2 ;
    case COURSEIMAGE = 3 ;
    case COURSEINTRODUCTIONVIDEO = 4 ;
    case COURSECOVERIMAGE = 5 ;
}
