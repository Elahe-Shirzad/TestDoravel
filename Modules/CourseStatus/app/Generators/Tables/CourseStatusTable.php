<?php

namespace Modules\CourseStatus\Generators\Tables;

use Dornica\BladeComponents\Foundation\Enums\Variant;
use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\PanelKit\Generator\Table\BaseTable;
use Dornica\PanelKit\Generator\Table\Enums\Driver;
use Dornica\PanelKit\Generator\Table\Exceptions\InvalidDriverResourceException;
use Exception;
use Modules\Bank\Enums\BooleanState;
use Modules\CourseStatus\Models\CourseStatus;
use Dornica\Foundation\Core\Enums\IsActive;


class CourseStatusTable extends BaseTable
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
            ->setResource(CourseStatus::class);
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
            name: 'code',
            label: __('basemodule::field.code'),
            type: function ($value, $model) {
                return renderUrl(
                    value: $value,
                    showPermission: "admin.system-settings.course-settings.course-statuses.edit",
                    editPermission: "admin.system-settings.course-settings.course-statuses.edit",
                    showParameters: ['status' => encryptValue($model->id)],
                    editParameters: ['status' => encryptValue($model->id)],
                );
            },
            sortable: false
        );

        $this->addColumn(
            name: 'color',
            label: __('basemodule::field.color'),
            type: renderColor(),
            sortable: false,
            mergeGroup: 'nameAndColor'
        );

        $this->addColumn(
            name: 'name',
            label: __('basemodule::field.name'),
            sortable: 'true',
            mergeGroup: 'nameAndColor',
        );

        $this->addColumn(
            name: 'transfer_status_access',
            label: __('basemodule::field.statuses_to_transfer'),
            type: function ($value, $model) { // NOSONAR - infrastructure
                return optional($model->courseStatusAccesses)
                    ->map(function ($statusAccess) {
                        return renderComponent(Badge::class, [
                            'value' => $statusAccess->name,
                            'variant' => "secondary",
                            "appearance" => 'light',
                            'size' => "sm"
                        ]);
                    })
                    ?->implode(' ') ?? '';
            },
            sortable: false,
            class: 'flex-wrap',
        );

        $this->addColumn(
            name: 'is_start',
            label: __('basemodule::field.statuses.is_start'),
            type: function ($value, $entity) { // NOSONAR: need both $value and $entity for function parameter
                return renderComponent(Badge::class, [
                    "value"=>getEnumName(BooleanState::class,$value,'basemodule'),
                    "variant" => stateBadgeVariant($value->value),
                    "appearance" => 'light',
                    "size" => "sm"
                ]);
            },
            sortable: 'true'
        );

        $this->addColumn(
            name: 'is_end',
            label: __('basemodule::field.statuses.is_end'),
            type: function ($value, $entity){
                return renderComponent(Badge::class,[
                    'value'=>getEnumName(BooleanState::class,$value,'basemodule'),
                    'variant'=>stateBadgeVariant($value->value),
                    'appearance'=>'light',
                    'size'=>'sm',
                ]);
            },
            sortable: 'true'
        );

        $this->addColumn(
            name: 'is_count',
            label: __('basemodule::field.statuses.is_count'),
            type: function($value, $entity){
                return renderComponent(Badge::class,[
                    'value'=>getEnumName(BooleanState::class,$value,'basemodule'),
                    'variant'=>stateBadgeVariant($value->value),
                    'appearance'=>'light',
                    'size'=>'sm',
                ]);
            },
            sortable: 'true'
        );

        $this->addColumn(
            name: 'can_update',
            label: __('basemodule::field.statuses.can_update'),
            type: function($value, $entity){
                return renderComponent(Badge::class,[
                    'value'=>getEnumName(BooleanState::class,$value,'basemodule'),
                    'variant'=>stateBadgeVariant($value->value),
                    'appearance'=>'light',
                    'size'=>'sm',
                ]);
            },
            sortable: 'true'
        );

        $this->addColumn(
            name: 'can_delete',
            label: __('basemodule::field.statuses.can_delete'),
            type: function($value, $entity){
                return renderComponent(Badge::class,[
                    'value'=>getEnumName(BooleanState::class,$value,'basemodule'),
                    'variant'=>stateBadgeVariant($value->value),
                    'appearance'=>'light',
                    'size'=>'sm',
                ]);
            },
            sortable: 'true'
        );

        $this->addColumn(
            name: 'is_publish',
            label: __('basemodule::field.statuses.is_publish'),
            type: function($value, $entity){
                return renderComponent(Badge::class,[
                    'value'=>getEnumName(BooleanState::class,$value,'basemodule'),
                    'variant'=>stateBadgeVariant($value->value),
                    'appearance'=>'light',
                    'size'=>'sm',
                ]);
            },
            sortable: 'true'
        );

        $this->addColumn(
            name:'sort',
            label:__('basemodule::field.sort'),
            sortable: 'true',
        );
        $this->addColumn(
            name: 'is_active',
            label: __('basemodule::field.is_active'),
            type: function($value, $entity){
                return renderComponent(Badge::class,[
                    'value'=>getEnumName(IsActive::class,$value,'basemodule'),
                    'variant'=>stateBadgeVariant($value->value),
                    'appearance'=>'light',
                    'size'=>'sm',
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
            name: 'code',
            elementName: 'filter_code',
            elementType: 'text',
            label: __('basemodule::field.code'),
            operator: '%',
        );

        $this->addFilter(
            name: 'name',
            elementName: 'filter_name',
            elementType: 'text',
            label: __('basemodule::field.name'),
            operator: '%',
        );

        $this->addFilter(
            name: '',
            elementName: 'filter_transfer_status_access',
            elementType: 'multiselect',
            label: __('basemodule::field.statuses_to_transfer'),
            operator: 'in',
            data: prepareSelectComponentData(
                CourseStatus::dynamicAvailable(only_actives: false)
            ),
            options: [
                'customQuery' => function ($queryBuilder, $selected) {
                    $queryBuilder->whereHas("courseStatusAccesses", function ($subQuery) use ($selected) {
                        $subQuery->whereIn("child_course_status_id", $selected);
                    });
                }
            ]
        );

//        $this->addFilter(
//            name: 'is_start',
//            elementName: 'filter_is_start',
//            elementType: 'select',
//            label: __('basemodule::field.statuses.is_start'),
//            data: BooleanState::selectComponentItems(moduleName: 'basemodule')
//        );

        $this->addFilter(
            name:'is_start`' ,
            label: __('basemodule::field.statuses.is_start'),
            elementName: 'filter_is_start',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: BooleanState::class,
                moduleName: 'basemodule'
            ),
        );

        $this->addFilter(
            name:'is_end' ,
            label: __('basemodule::field.statuses.is_end'),
            elementName: 'filter_is_end',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: BooleanState::class,
                moduleName: 'basemodule'
            ),
        );

        $this->addFilter(
            name:'is_count' ,
            label: __('basemodule::field.statuses.is_count'),
            elementName: 'filter_is_count',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: BooleanState::class,
                moduleName: 'basemodule'
            ),
        );


        $this->addFilter(
            name:'can_update' ,
            label: __('basemodule::field.statuses.can_update'),
            elementName: 'filter_can_update',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: BooleanState::class,
                moduleName: 'basemodule'
            ),
        );

        $this->addFilter(
            name:'can_delete' ,
            label: __('basemodule::field.statuses.can_delete'),
            elementName: 'filter_can_delete',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: BooleanState::class,
                moduleName: 'basemodule'
            ),
        );

        $this->addFilter(
            name:'is_publish' ,
            label: __('basemodule::field.statuses.is_publish'),
            elementName: 'filter_is_publish',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: BooleanState::class,
                moduleName: 'basemodule'
            ),
        );

        $this->addFilter(
            name:'is_active' ,
            label: __('basemodule::field.statuses.is_active'),
            elementName: 'filter_is_active',
            elementType:'select' ,
            data:prepareSelectComponentData(
                source: IsActive::class,
                moduleName: 'basemodule'
            ),
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
                return route('admin.system-settings.course-settings.course-statuses.edit', encryptValue($entity->id));
            },
            permission: 'admin.system-settings.course-settings.course-statuses.edit'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'حذف',
            target: function ($entity) {
                return route('admin.system-settings.course-settings.course-statuses.destroy', encryptValue($entity->id));
            },
            targetMethod: 'DELETE',
            variant: Variant::DANGER,
            class: 'text-danger',
            permission: 'admin.system-settings.course-settings.course-statuses.destroy',
            confirmation: true,
            confirmationType: 'danger',
            disabled: function ($entity) {
                if ($entity->courseStatusAccesses()->count() > 0 || $entity->is_start->value) {
                    return true;
                }
                return false;
            },
            disabledTooltip: function ($entity) {
                $message = __('basemodule::message.not_allow_dependency');
                if ($entity->is_start->value) {
                    $message = __('basemodule::message.impossible_delete_when_is_start_true');
                } elseif ($entity->courseStatusAccesses()->count() > 0) {
                    $message = __('basemodule::message.delete_not_allow_cause_dependencies_with_param', [
                        'sectionName' => __('basemodule::field.statuses_to_transfer')
                    ]);

                }
                return $message;
            },
            confirmationMessage: __('basemodule::message.delete_confirmation_message'),
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
            title: "افزودن وضعیت دوره جدید",
            route: route('admin.system-settings.course-settings.course-statuses.create'),
            permission: 'admin.system-settings.course-settings.course-statuses.create'
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
