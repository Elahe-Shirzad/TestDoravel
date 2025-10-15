<?php

namespace Modules\BaseModule\Enums\General;

use Dornica\Foundation\Core\Traits\EnumTools;

enum ResourceType: string
{
    use EnumTools;

    case TEACHER = 'teacher';
    case BOOK = 'book';
    case BLOG = 'blog';
    case TICKET = 'ticket';
    case QUESTION = 'question';
    case SUBJECT = 'subject';
    case SUBJECT_CONTENT = 'subject_content';
    case PAGE = 'page';
    case COURSE_MEMBER = 'course_member';
    case COURSE = 'course';
}
