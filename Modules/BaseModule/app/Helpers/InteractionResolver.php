<?php

namespace Modules\BaseModule\Helpers;

use App\Models\SubjectContentRate;
use Modules\BaseModule\Http\Resources\BlogResource;
use Modules\BaseModule\Http\Resources\BookResource;
use Modules\BaseModule\Http\Resources\CourseMemberResource;
use Modules\BaseModule\Http\Resources\CourseResource;
use Modules\BaseModule\Http\Resources\PageResource;
use Modules\BaseModule\Http\Resources\QuestionResource;
use Modules\BaseModule\Http\Resources\RateResource;
use Modules\BaseModule\Http\Resources\ReactionResource;
use Modules\BaseModule\Http\Resources\ResponseResource;
use Modules\BaseModule\Http\Resources\SubjectResource;
use Modules\BaseModule\Http\Resources\TeacherResource;
use Modules\BaseModule\Http\Resources\TicketResource;
use Modules\BaseModule\Http\Resources\ViewResource;
use Modules\Blog\Models\Blog;
use Modules\Blog\Models\BlogComment;
use Modules\Blog\Models\BlogFavorite;
use Modules\Blog\Models\BlogRate;
use Modules\Blog\Models\BlogReaction;
use Modules\Blog\Models\BlogRelated;
use Modules\Blog\Models\BlogView;
use Modules\BlogStatus\Models\BlogStatus;
use Modules\Book\Models\Book;
use Modules\Book\Models\Subject;
use Modules\Book\Models\SubjectContentReaction;
use Modules\BookStatus\Models\BookStatus;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseMember;
use Modules\Course\Models\CourseRate;
use Modules\Course\Models\CourseView;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\Page\Models\PageReaction;
use Modules\Question\Models\QuestionReaction;
use Modules\Question\Models\QuestionResponse;
use Modules\Question\Models\QuestionView;
use Modules\Teacher\Models\Teacher;
use Modules\TeacherStatus\Models\TeacherStatus;
use Modules\Ticket\Models\Ticket;
use Modules\TicketStatus\Models\TicketStatus;

class InteractionResolver
{
    /**
     * @param string $interactionType
     * @param string $resourceType
     * @return string|null
     */
    public static function model(string $interactionType, string $resourceType): ?string
    {
        return match ("$interactionType:$resourceType") {
            'view:blog' => BlogView::class,
            'reaction:blog' => BlogReaction::class,
            'favorite:blog' => BlogFavorite::class,
            'rate:blog' => BlogRate::class,
            'comment:blog' => BlogComment::class,
            'related:blog' => BlogRelated::class,
            'reaction:question' => QuestionReaction::class,

            'model:teacher_status' => TeacherStatus::class,
            'model:teacher' => Teacher::class,

            'model:book_status' => BookStatus::class,
            'model:book' => Book::class,
            'reaction:book' => SubjectContentReaction::class,

            'model:course_status' => CourseStatus::class,
            'model:course' => Course::class,

            'model:ticket_status' => TicketStatus::class,
            'model:ticket' => Ticket::class,

            'model:blog_status' => BlogStatus::class,
            'model:blog' => Blog::class,
            'favorite:question' => QuestionView::class,
            'response:question' => QuestionResponse::class,

            'view:subject' => Subject::class,

            'rate:subject_content' => SubjectContentRate::class,

            'reaction:page' => PageReaction::class,

            'model:course_member' => CourseMember::class,

            'view:course' => CourseView::class,
            'rate:course' => CourseRate::class,

            default => null
        };
    }

    /**
     * @param string $resourceType
     * @return string|null
     */
    public static function resource(string $resourceType): ?string
    {
        return match ($resourceType) {
            'teacher' => TeacherResource::class,
            'book' => BookResource::class,
            'ticket' => TicketResource::class,
            'course' => CourseResource::class,
            'course_member' => CourseMemberResource::class,
            'blog' => BlogResource::class,
            'reaction' => ReactionResource::class,
            'rate' => RateResource::class,
            'view' => ViewResource::class,
            'question' => QuestionResource::class,
            'response' => ResponseResource::class,
            'subject' => SubjectResource::class,
            'page' => PageResource::class,
            default => null
        };
    }
}
