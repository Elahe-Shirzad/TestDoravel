<?php

namespace Modules\Bank\Generators\Tables;

use Dornica\BladeComponents\Foundation\Enums\Variant;
use Dornica\BladeComponents\UI\Badge\Badge;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\PanelKit\Generator\Table\BaseTable;
use Dornica\PanelKit\Generator\Table\Enums\Driver;
use Dornica\PanelKit\Generator\Table\Exceptions\InvalidDriverResourceException;
use Exception;
use Modules\Bank\Enums\BankType;
use Modules\Bank\Models\Bank;

class BankTable extends BaseTable
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
            ->setResource(Bank::class);
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
            label: 'رنگ سفارشی',
            type: renderColor(),
            sortable: false,
        );
        $this->addColumn(
            name: 'name',
            label: 'نام',
            type: 'text',
            sortable: true
        );
        $this->addColumn(
            name: 'image_id',
            label: 'تصویر',
            type: 'image'
        );

        $this->addColumn(
            name: 'code',
            label: 'کد',
            type: 'text',
            sortable: true
        );

        $this->addColumn(
            name: 'sort',
            label: 'مرتب سازی',
        );

        $this->addColumn(
            name: 'type',
            label: 'کد',
            type: function ($value, $entity) { // NOSONAR: need both $value and $entity for function parameter
                return renderComponent(Badge::class, [
                    "value" => getEnumName(BankType::class, $value, 'bank'),
                    "variant" => stateBadgeVariant($value->value),
                    "appearance" => 'light',
                    "size" => "sm"
                ]);
            }
        );

        $this->addColumn(
            name: 'is_active',
            label: 'وضعیت',
            type: function ($value, $entity) { // NOSONAR: need both $value and $entity for function parameter
                return renderComponent(Badge::class, [
                    "value" => getEnumName(IsActive::class, $value, 'bank'),
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
            name: 'name',
            elementName: 'filter_name',
            elementType: 'text',
            label: 'عنوان',
            operator: '%'
        );

        $this->addFilter(
            name: 'code',
            elementName: 'filter_code',
            elementType: 'text',
            label: 'کد',
            operator: '%',
            options: [
                'direction' => 'ltr'
            ]
        );

        $this->addFilter(
            name: 'is_active',
            elementName: 'filter_is_active',
            elementType: 'select',
            label: 'وضعیت',
            data: prepareSelectComponentData(
                source: IsActive::class,
                moduleName: 'bank'
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
            title: 'جزئیات',
            target: function ($entity) {
                return route('admin.base-information.banks.show', encryptValue($entity->id));
            },
            permission: 'admin.base-information.banks.show'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'ویرایش',
            target: function ($entity) {
                return route('admin.base-information.banks.edit', encryptValue($entity->id));
            },
            permission: 'admin.base-information.banks.edit'
        );

        $this->addColumnAction(
            type: 'link',
            title: 'حذف',
            target: function ($entity) {
                return route('admin.base-information.banks.destroy', encryptValue($entity->id));
            },
            targetMethod: 'DELETE',
            variant: Variant::DANGER,
            class: 'text-danger',
            permission: 'admin.base-information.banks.destroy',
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
            title: 'درج بانک جدید',
            route: route('admin.base-information.banks.create'),
            icon: 'fa-solid fa-plus',
            variant: 'success',
            permission: 'admin.base-information.banks.create',
        );

        $this->addToolbarButton(
            title: 'درج بانک با مودال',
            route: route('admin.base-information.banks.create'),
            icon: 'fa-solid fa-plus',
            variant: 'success',
            attributes: [
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#myModal',
                'class' => 'custom-class',
            ],
            permission: 'admin.base-information.banks.create'
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
