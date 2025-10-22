<?php

namespace Modules\Course\Generators\Tables;

use Dornica\BladeComponents\Foundation\Enums\Variant;
use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\Generator\Table\BaseTable;
use Dornica\PanelKit\Generator\Table\Enums\Driver;
use Dornica\PanelKit\Generator\Table\Exceptions\InvalidDriverResourceException;
use Exception;
use Modules\Course\Models\Course;
use Modules\CourseCategory\Models\CourseCategory;
use Modules\CourseLevel\Models\CourseLevel;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\Location\Enums\Service;

class CourseTable extends BaseTable
{
    /**
     * Initialize the table with its driver and resource model.
     *
     * @return void
     *
     * @throws Exception
     * @throws InvalidDriverResourceException
     */
    public function __construct()
    {
        $this
            ->setDriver(Driver::MODEL)
            ->setResource(Course::class);
    }

    /**
     * Define the columns for the table.
     *
     * Use `$this->addColumn()` to add each column.
     *
     * @return void
     */
    public function columns(): void
    {
        $this->addColumn(
            name: 'title',
            label: __('course::field.course_title'),
            type: function ($value, $entity) {
                return renderUrl(
                    value: trimString($value, size: 41),
                    showPermission: 'admin.courses.show',
                    editPermission: 'admin.courses.edit',
                    showParameters: ['course' => encryptValue($entity->id)],
                    editParameters: ['course' => encryptValue($entity->id)],
                );
            },
            sortable: 'true',

            class: 'd-block',
            mergeGroup: 'course_merge',
            options: [
                'groupBreakLine' => true,
                'groupOrientation' => 'horizontal',
            ],
        );

        $this->addColumn(
            name: 'id',
            label: __('course::field.course_id'),
            type: function ($value, $entity){
                return renderComponent(Badge::class, [
                    "value" => $entity->id,
                "variant" => 'info',
                "appearance" => 'light',
                "size" => "sm"
            ]);
                },
            sortable: 'true',
            mergeGroup: 'course_merge',
        );

        $this->addColumn(
            name: 'title:courseCategory',
            label: __('course::field.course_category'),
            type: function ($value, $entity){
                return renderComponent(Badge::class, [
                    "value" => $entity->courseCategory->title,
                    "variant" => 'success',
                    "appearance" => 'light',
                    "size" => "sm"
                ]);
            },
            sortable: 'true',
        );

        $this->addColumn(
            name: 'title:courseLevel',
            label: __('course::field.course_category_level'),
            sortable: 'true',
        );
        $this->addColumn(
            name: 'total_course_content',
            label: __('course::field.total_course_content'),
            sortable: 'true',
        );

        $this->addColumn(
            name: 'total_duration',
            label: __('course::field.total_duration'),
            type: function ($value, $entity) {
                return '<div class="d-flex flex-column">' .
                    '<span>' . $value . ' ' . __('course::field.minute') . '</span>' .
                    ($value > 60
                        ? '<br><small class="text-gray-500">' . formatMinutesToHours($value) . '</small>'
                        : ''
                    ) .
                    '</div>';
            },
            sortable: 'true',
        );

        $this->addColumn(
            name: 'name:courseStatus',
            label: __('course::field.course_category'),
            type: function ($value, $entity){
                return renderComponent(Badge::class, [
                    "value" => $entity->courseStatus->name,
                    "customColor" => $entity->courseStatus->color,
                    "appearance" => 'light',
                    "size" => "sm"
                ]);
            },
        );
    }

    /**
     * Define the filters available for this table.
     *
     * Use `$this->addFilter()` to add each filter.
     *
     * @return void
     */
    public function filters(): void
    {
        $this->addFilter(
            name: 'title',
            elementName: 'filter_title',
            elementType: 'text',
            label: __('course::field.course_title'),
            operator: '%',
            class: "col-md-6"
        );

        $this->addFilter(
            name: "course_category_id",
            elementName: "filter_course_category_id",
            elementType: "select",
            label: __("course::field.course_category"),
            class: "col-md-6",
            data: prepareSelectComponentData(
                source: CourseCategory::class,
                labelColumn: 'title',
                moduleName: 'course'
            ),
            options: [
                'id' => 'filter_course_category_id-select'
            ],
        );


        $this->addFilter(
            name: 'total_duration',
            elementName: 'filter_total_duration',
            elementType: 'text',
            label: __('course::field.total_duration'),
            operator: '%',
            class: "col-md-6"
        );

        $this->addFilter(
            name: 'total_course_content',
            elementName: 'filter_total_course_content',
            elementType: 'text',
            label: __('course::field.total_course_content'),
            operator: '%',
            class: "col-md-6"
        );



        $this->addFilter(
            name: "course_level_id",
            elementName: "filter_course_level_id",
            elementType: "select",
            label: __("course::field.course_category"),
            class: "col-md-6",
            data: prepareSelectComponentData(
                source: CourseLevel::class,
                labelColumn: 'title',
                moduleName: 'course'
            ),
            options: [
                'id' => 'filter_course_level_id-select'
            ],
        );


        $this->addFilter(
            name: "course_status_id",
            elementName: "filter_course_status_id",
            elementType: "select",
            label: __("course::field.course_category"),
            class: "col-md-6",
            data: prepareSelectComponentData(
                source: CourseStatus::class,
                labelColumn: 'name',
                moduleName: 'course'
            ),
            options: [
                'id' => 'filter_course_status_id-select'
            ],
        );


    }

    /**
     * Define the column actions available for each row.
     *
     * Use `$this->addColumnAction()` to add actions like edit or delete.
     *
     * @return void
     */
    public function columnActions(): void
    {
        $this->addColumnAction(
            type: 'link',
            title: 'جزئیات',
            target: function ($entity) {
                return route('admin.courses.show', encryptValue($entity->id));
            },
            permission: 'admin.courses.management.index'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'ویرایش',
            target: function ($entity) {
                return route('admin.courses.edit', encryptValue($entity->id));
            },
            permission: 'admin.courses.management.index'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'حذف',
            target: function ($entity) {
                return route('admin.courses.destroy', encryptValue($entity->id));
            },
            targetMethod: 'DELETE',
            variant: Variant::DANGER,
            class: 'text-danger',
            permission: 'admin.courses.management.index',
            confirmation: true,
            confirmationType: 'danger'
        );
    }

    /**
     * Define the toolbar buttons for this table.
     *
     * Use `$this->setToolbarButton()` to add buttons.
     *
     * @return void
     */
    public function toolbar(): void
    {
         $this->addToolbarButton(
        title: __("course::operation.add_course"),
        route: route('admin.courses.create'),
        permission: "admin.courses.create"
    );
    }

    /**
     * Define bulk operations for selected rows.
     *
     * Use `$this->addBulkOperation()` to add bulk actions.
     *
     * @return void
     */
    public function bulkOperations(): void
    {
        //
    }
}
