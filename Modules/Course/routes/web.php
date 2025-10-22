<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;
use App\Support\Excluded;
use Modules\BaseModule\Enums\General\BooleanState;
//use Modules\Course\Http\Controllers\CourseChapterController;
//use Modules\Course\Http\Controllers\CourseCommentController;
//use Modules\Course\Http\Controllers\CourseContentController;
//use Modules\Course\Http\Controllers\CourseMemberController;
use Modules\Course\Models\Course;
use Modules\CourseStatus\Models\CourseStatus;

Route::middleware('check.workflow')->group(function () {
    $appendRoutes = [];

    if (!App::runningConsoleCommand(Excluded::CONSOLE_COMMANDS)) {
        foreach (systemStorage()->get('course', 'statuses', function () {
            return CourseStatus::available();
        }) as $status) {
//dd($status);
            Route::get("/management/{$status['code']}", [CourseController::class, 'byStatus'])
                ->name($status['code'])
                ->title($status['name'])
                ->permission('admin.courses.management.index')
                ->visibility(function () use ($status) {

                    $courseWorkflowStatuses = getUserCurrentRoleWorkflow('course');
                    return $courseWorkflowStatuses->get('view')?->contains($status['id']);
                })
                ->badge(
                    value: function () use ($status) {
                        return $status['is_count'] == BooleanState::YES->value ? statusCounterInSideBar(
                            model: Course::class,
                            columnId: $status['id'],
                            columnName: 'course_status_id',
                            section: 'course'
                        ) : null;
                    },
                    style: 'light'
                );
            $appendRoutes[] = "admin.courses.{$status['code']}";
        }

        Route::get('/management', [CourseController::class, 'index'])
            ->name('management.index')
            ->title('course::section.all_courses')
            ->badge(
                value: function () {
                    return statusCounterInSideBar(
                        model: Course::class,
                        columnName: 'course_status_id',
                        countAll: true,
                        section: 'course'
                    );
                },
                style: 'light'
            )
            ->showInSidebar()
            ->appendRoutes($appendRoutes);
    }

    Route::get('/create', [CourseController::class, 'create'])
        ->name('create')
        ->title('course::operation.add_course')
        ->showInSidebar();

    Route::post('/store', [CourseController::class, 'store'])
        ->name('store')
        ->title('course::operation.store_course');

    Route::prefix('{course}')->group(function () {
        Route::get('/show', [CourseController::class, 'show'])
            ->name('show')
            ->parentRoute('admin.courses.management.index')
            ->title('course::section.show_course');

        Route::get('/edit', [CourseController::class, 'edit'])
            ->name('edit')
            ->parentRoute('admin.courses.management.index')
            ->title('course::section.edit_course');

        Route::put('/update', [CourseController::class, 'update'])
            ->name('update')
            ->title('course::section.update_course');

        Route::get('/seo', [CourseController::class, 'seo'])
            ->name('seo.edit')
            ->title('course::section.course_seo')
            ->parentRoute('admin.courses.management.index');

        Route::patch('/seo', [CourseController::class, 'seoUpdate'])
            ->name('seo.update')
            ->title('course::operation.update_course_seo');

        Route::get('/settings', [CourseController::class, 'setting'])
            ->name('settings.edit')
            ->title('course::section.course_general_settings')
            ->parentRoute('admin.courses.management.index');

        Route::patch('/settings', [CourseController::class, 'settingUpdate'])
            ->name('settings.update')
            ->title('course::operation.update_course_general_settings');

        Route::get('/views', [CourseController::class, 'views'])
            ->name('views')
            ->title('basemodule::section.views')
            ->parentRoute('admin.courses.management.index');

        Route::get('/rates', [CourseController::class, 'rates'])
            ->name('rates')
            ->title('basemodule::section.scorers')
            ->parentRoute('admin.courses.management.index');

        Route::get('/status-logs', [CourseController::class, 'statusLogs'])
            ->name('status-logs')
            ->title('basemodule::section.status_logs')
            ->parentRoute('admin.courses.management.index');

        Route::patch('/change-status', [CourseController::class, 'changeStatus'])
            ->name('change-status')
            ->parentRoute('admin.courses.management.index');

        Route::get('/logs', [CourseController::class, 'logs'])
            ->name('logs')
            ->title('basemodule::section.logs')
            ->parentRoute('admin.courses.management.index');

        Route::delete('/destroy', [CourseController::class, 'destroy'])
            ->name('destroy')
            ->title('course::section.destroy_course');
//
//        Route::get('/chapters', [CourseChapterController::class, 'index'])
//            ->name('chapters.index')
//            ->parentRoute('admin.courses.management.index')
//            ->title('مدیریت فصل ها');
//
//        Route::post('/chapters/store', [CourseChapterController::class, 'store'])
//            ->name('chapters.store')
//            ->parentRoute('admin.courses.management.index')
//            ->title('عملیات درج فصل جدید');
//
//        Route::post('/chapters/search', [CourseChapterController::class, 'search'])
//            ->name('chapters.search')
//            ->parentRoute('admin.courses.management.index')
//            ->title('عملیات درج فصل جدید');
//
//        Route::prefix('/chapters/{chapter}')
//            ->as('chapters.')
//            ->group(function () {
//                Route::get('show', [CourseChapterController::class, 'show'])
//                    ->name('show')
//                    ->parentRoute('admin.courses.management.index')
//                    ->title('جزئیات فصل');
//
//                Route::get('edit', [CourseChapterController::class, 'edit'])
//                    ->name('edit')
//                    ->parentRoute('admin.courses.management.index')
//                    ->title('ویرایش فصل');
//
//                Route::post('update', [CourseChapterController::class, 'update'])
//                    ->name('update')
//                    ->parentRoute('admin.courses.management.index')
//                    ->title('عملیات ویرایش فصل');
//
//                Route::delete('destroy', [CourseChapterController::class, 'destroy'])
//                    ->name('destroy')
//                    ->parentRoute('admin.courses.management.index')
//                    ->title('جزئیات فصل');
//
//                Route::prefix('contents')
//                    ->as('contents.')
//                    ->controller(CourseContentController::class)
//                    ->group(function () {
//                        Route::get('/', 'index')
//                            ->name('index')
//                            ->parentRoute('admin.courses.management.index')
//                            ->parentRoute('admin.courses.chapters.index')
//                            ->title('basemodule::field.sessions');
//
//                        Route::post('/search', 'search')
//                            ->name('search')
//                            ->parentRoute('admin.courses.management.index')
//                            ->parentRoute('admin.courses.chapters.index')
//                            ->title('عملیات فیلتر لیست جلسات');
//
//                        Route::get('/create', 'create')
//                            ->name('create')
//                            ->parentRoute('admin.courses.management.index')
//                            ->parentRoute('admin.courses.chapters.index')
//                            ->parentRoute('admin.courses.chapters.contents.index')
//                            ->title('افزودن جلسه جدید');
//
//                        Route::post('/store', 'store')
//                            ->name('store')
//                            ->title('عملیات افزودن جلسه جدید');
//
//                        Route::prefix('{content}')
//                            ->group(function () {
//                                Route::get('/show', 'show')
//                                    ->name('show')
//                                    ->parentRoute('admin.courses.management.index')
//                                    ->parentRoute('admin.courses.chapters.index')
//                                    ->parentRoute('admin.courses.chapters.contents.index')
//                                    ->title('جزئیات جلسه');
//
//                                Route::get('/edit', 'edit')
//                                    ->name('edit')
//                                    ->parentRoute('admin.courses.management.index')
//                                    ->parentRoute('admin.courses.chapters.index')
//                                    ->parentRoute('admin.courses.chapters.contents.index')
//                                    ->title('ویرایش جلسه');
//
//                                Route::put('/update', 'update')
//                                    ->name('update')
//                                    ->title('عملیات ویرایش جلسه');
//
//                                Route::get('/settings', 'setting')
//                                    ->name('settings.edit')
//                                    ->title('course::section.course_content_general_settings')
//                                    ->parentRoute('admin.courses.management.index')
//                                    ->parentRoute('admin.courses.chapters.index')
//                                    ->parentRoute('admin.courses.chapters.contents.index');
//
//                                Route::patch('/settings', 'settingUpdate')
//                                    ->name('settings.update')
//                                    ->title('course::operation.update_course_content_general_settings');
//
//                                Route::delete('/destroy', 'destroy')
//                                    ->name('destroy')
//                                    ->title('عملیات ویرایش جلسه');
//                            });
//                    });
//            });

    });

//    Route::prefix('{course}/comments')
//        ->as('comments.')
//        ->controller(CourseCommentController::class)
//        ->group(function () {
//            Route::get('/', 'index')
//                ->name('index')
//                ->title('course::section.sent_comments')
//                ->parentRoute('admin.courses.management.index');
//
//            Route::delete('/destroy/{course_comment}', 'destroy')
//                ->name('destroy');
//        });
//
//    Route::prefix('{course}/members')
//        ->as('members.')
//        ->controller(CourseMemberController::class)
//        ->group(function () {
//            Route::get('/', 'index')
//                ->name('index')
//                ->parentRoute('admin.courses.management.index')
//                ->title('course::section.course_members');
//        });
});

