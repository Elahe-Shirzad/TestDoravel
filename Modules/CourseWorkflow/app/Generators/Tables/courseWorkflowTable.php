<?php

namespace Modules\CourseWorkflow\Generators\Tables;

use App\Models\Role;
use Dornica\BladeComponents\Foundation\Enums\Variant;
use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\Generator\Table\BaseTable;
use Dornica\PanelKit\Generator\Table\Enums\Driver;
use Dornica\PanelKit\Generator\Table\Exceptions\InvalidDriverResourceException;
use Exception;
use Modules\CourseWorkflow\Models\CourseWorkflow;

class courseWorkflowTable extends BaseTable
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
            ->setResource(CourseWorkflow::class);
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
            name: 'name:role',
            label: __('basemodule::field.admin'),
            type: function ($value, $entity) { // NOSONAR: need both $value and $entity for function parameter
                return renderUrl(
                    value: trimString($entity->role->name),
                    editPermission: 'admin.system-settings.course-settings.course-workflows.edit',
                    editParameters: ['course_workflow' => encryptValue($entity->id)],
                );
            },
            sortable: 'true',
        );

        $this->addColumn(
            name: 'start_date',
            label: __('basemodule::field.start_date'),
            options: [
                'dateFormat' => 'Y/m/d'
            ]
        );
        $this->addColumn(
            name: 'end_date',
            label: __('basemodule::field.end_date'),
            options: [
                'dateFormat' => 'Y/m/d'
            ]
        );

        $this->addColumn(
            name: 'is_active',
            label: __('basemodule::field.is_active'),
            type: function ($value, $entity) {
                return renderComponent(Badge::class, [
                    'value' => getEnumName(IsActive::class, $value, 'basemodule'),
                    'variant' => stateBadgeVariant($value->value),
                    'appearance' => 'light',
                    'size' => 'sm',
                ]);
            },
            sortable: 'true'
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
            name: "role_id",
            elementName: "filter_role",
            elementType: "select",
            label: __('basemodule::field.user_role'),
            data: prepareSelectComponentData(
                source: Role::select('id','name')->dynamicAvailable(only_actives: false),
                moduleName: 'basemodule'
            )
        );

        $this->addFilter(
            name: 'is_active',
            elementName: 'filter_is_active',
            elementType: 'select',
            label: __('basemodule::field.is_active'),
            data: prepareSelectComponentData(
                source: IsActive::class,
                moduleName: 'basemodule'
            ),

        );


        $this->addFilter(
            name: "start_date",
            elementName: "filter_start_date_range",
            elementType: "datetime",
            range: true,
            class: "col-md-6",
            options: [
                'type' => 'date',
                'dateFormat' => 'YYYY/MM/DD',
                'fromLabel' => __('basemodule::field.start_date'),
                'fromPrefix' => __('basemodule::field.from'),
                'toPrefix' => __('basemodule::field.to'),
                'allowSameDaySelection' => true,
            ]
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
            title: 'ویرایش',
            target: function ($entity) {
                return route('admin.system-settings.course-settings.course-workflows.edit', encryptValue($entity->id));
            },
            permission: 'admin.system-settings.course-settings.course-workflows.edit'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'حذف',
            target: function ($entity) {
                return route('admin.system-settings.course-settings.course-workflows.destroy', encryptValue($entity->id));
            },
            targetMethod: 'DELETE',
            variant: Variant::DANGER,
            class: 'text-danger',
            permission: 'admin.system-settings.course-settings.course-workflows.destroy',
            confirmation: true,
            confirmationMessage: __('basemodule::message.delete_confirmation_message'),
            confirmationType: 'danger',
            confirmationIcon: 'fa-regular fa-trash-can',
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
            title: "افزودن گردش کار دوره ها",
            route: route('admin.system-settings.course-settings.course-workflows.create'),
            permission: 'admin.system-settings.course-settings.course-workflows.create'
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
