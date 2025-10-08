<?php

namespace Modules\Location\Generators\Tables;

use Dornica\BladeComponents\Foundation\Enums\Variant;
use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\Generator\Table\BaseTable;
use Dornica\PanelKit\Generator\Table\Enums\Driver;
use Dornica\PanelKit\Generator\Table\Exceptions\InvalidDriverResourceException;
use Exception;
use Modules\Bank\Models\Location;
use Modules\BaseModule\Enums\General\UserType;
use Modules\Location\Enums\Service;

class LocationTable extends BaseTable
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
            ->setResource(Location::class);
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
            name: 'color',
            label: __('location::general.color'),
            type: renderColor(),
            sortable: false,
        );

        $this->addColumn(
            name: 'branch',
            label: __('location::general.branch'),
            mergeGroup:"bank_branch",
            bodyAlign: "center",
            centered:true,
            options: [
                'groupHeadTooltip' => 'این ستون ها شامل اطلاعات مربوط به شعبه و تصویرآن است',
                'groupBreakLine' => true,
                'theadSeparator' => '/',
                'groupOrientation' => 'horizontal',
            ],
        );

        $this->addColumn(
            name: 'avatar_id',
            label: __('location::general.branch'),
            type: 'image',
            sortable: false,
            mergeGroup:"bank_branch",
        );

        $this->addColumn(
            name: 'square',
            label: __('location::general.square'),
            type: 'text',
            sortable: true
        );

        $this->addColumn(
            name: 'street',
            label: __('location::general.street'),
        );
        $this->addColumn(
            name: 'created_at',
            label: __('location::general.created_at'),
            type:'datetime',
            options: [
                'groupBodyTooltip' => fn($row) => groupBodyTooltip(
                    value: verta()->parse($row->updated_at)->format(jdateFormat()),
                    title: "تاریخ آخرین بروزرسانی",
                    valueClass: 'dir-ltr text-left'
                )
            ]
        );

        $this->addColumn(
            name: 'alley',
            label: __('location::general.alley'),
        );

        $this->addColumn(
            name: 'is_active',
            label:__('location::general.is_active'),
            type: function ($value, $entity) {
                $badgeColor = $entity->is_active->value === IsActive::YES->value ? 'info' : 'warning';
                // NOSONAR: need both $value and $entity for function parameter
                return renderComponent(Badge::class, [
                    "value" => getEnumName(IsActive::class, $value, 'location'),
                    "variant" => $badgeColor,
                    "appearance" => 'light',
                    "size" => "sm"
                ]);

            }
        );

        $this->addColumn(
            name: 'service',
            label:__('location::general.service'),
            type: function ($value, $entity) {

                // NOSONAR: need both $value and $entity for function parameter
                return renderComponent(Badge::class, [
                    "value" => getEnumName(Service::class, $value, 'location'),
                    "variant" => stateBadgeVariant($value->value),
                    "appearance" => 'light',
                    "size" => "sm"
                ]);
            }
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
            name: 'branch',
            elementName: 'filter_branch',
            elementType: 'text',
            label: __('location::general.square'),
            operator: '%'
        );

        $this->addFilter(
            name: 'square',
            elementName: 'filter_square',
            elementType: 'text',
            label:  __('location::general.street'),
            operator: '%',
            options: [
                'direction' => 'ltr',
        'prefix' => 'ریال',
        'hint' => 'مقدار عددی را وارد کنید',
            ]
        );

        $this->addFilter(
            name: 'is_active',
            withBreakLine:true,
            elementName: 'filter_is_active',
            elementType: 'select',
            label: __('location::general.is_active'),
            data: prepareSelectComponentData(
                source: IsActive::class,
                moduleName: 'location'
            ),
        );
        $this->addFilter(

            name: 'service',
            elementName: 'filter_service',
            elementType: 'radio_group',
            label:  __('location::general.service'),
            options: [
                'options' => Service::componentOptions('location'),
                'labelSpaceReserved' => true,
                'all_label' =>'همه خدمات'
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
                return route('admin.base-information.locations.show', encryptValue($entity->id));
            },
            permission: 'admin.base-information.locations.show',
            iconClass: "fa-regular fa-pen-to-square",

        );

        $this->addColumnAction(
            type: 'link',
            title: 'ویرایش',
            target: function ($entity) {
                return route('admin.base-information.locations.edit', encryptValue($entity->id));
            },
            permission: 'admin.base-information.locations.edit',
            confirmation: true,
            confirmationType: 'warning'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'حذف',
            target: function ($entity) {
                return route('admin.base-information.locations.destroy', encryptValue($entity->id));
            },
            targetMethod: 'DELETE',
            variant: Variant::DANGER,
            class: 'text-danger',
            permission: 'admin.base-information.locations.destroy',
            confirmation: true,
            confirmationType: 'danger',
            cancelButtonText:"لغو" ,
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
        //
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
