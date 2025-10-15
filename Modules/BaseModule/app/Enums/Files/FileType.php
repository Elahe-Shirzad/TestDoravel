<?php

namespace Modules\BaseModule\Enums\Files;

use Dornica\Foundation\Core\Traits\EnumTools;

enum FileType: int
{
    use EnumTools;
    case GENERAL = 0;
    case BLOG = 1;
    case SERVICE = 2;
    case MEDIA = 3;
    case FAQ = 4;
    case LINK = 5;
    case ADS = 6;
    case PRODUCT = 7;
    case SERVICE_CATEGORY = 8;
    case BLOG_CATEGORY = 9;
    case ADVERTISEMENT_CATEGORY = 10;
    case EVENT_CATEGORY = 11;
    case FAQ_CATEGORY = 12;
    case LINK_CATEGORY = 13;
    case MEDIA_CATEGORY = 14;
    case PAGE_CATEGORY = 15;
    case PLUGIN_CATEGORY = 16;
    case POLL_CATEGORY = 17;
    case PORTFOLIO_CATEGORY = 18;
    case PRODUCT_CATEGORY = 19;
    case SLIDESHOW_CATEGORY = 20;
    case TEAM_CATEGORY = 21;
    case THEME_CATEGORY = 22;
    case TICKET_CATEGORY = 23;
    case TEACHER = 24;
    case BOOK = 25;
    case TEACHER_DOCUMENT = 26;
    case QUESTION = 27;
    case BLOG_FILE = 28;
    case TICKET_ATTACHMENT = 29;
    case SUBJECT_CONTENT_FILE = 30;
    case SLIDESHOW = 31;
    case PAGE = 32;
    case COURSE = 33;
    case COURSE_CONTENT = 34;
    case COURSE_CATEGORY = 35;
    case TICKET_DEPARTMENT = 36;
}
