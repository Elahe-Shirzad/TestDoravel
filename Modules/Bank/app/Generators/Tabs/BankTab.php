<?php

namespace Modules\Bank\Generators\Tabs;

use Dornica\PanelKit\Generator\Tab\BaseTab;
use Dornica\PanelKit\Generator\Tab\Builders\Tab;

class BankTab extends BaseTab
{
    /**
     * Define the general route parameters for all tabs.
     *
     * If a tab does not have specific parameters, these will be used.
     *
     * @return array Route parameters.
     */
    public function routeParameters(): array
    {
        return [
            //
        ];
    }

    /**
     * Define the tabs and their configurations.
     *
     * Each tab should be created using the Tab builder.
     *
     * @return array List of tabs.
     */
    public function tabs(): array
    {
        $bankId = $this->bank->id;

        return [
            Tab::make('tab_bank')
                ->routeName( 'admin.base-information.banks.show' ?? 'admin.base-information.banks.edit')
                ->routeParameters(['bank' => encrypt($bankId)])
                ->title('بانک')
                ->subTitle(__('bank::general.bank_info'))
                ->permission('admin.base-information.banks.show'?? 'admin.base-information.banks.edit'),

            Tab::make('tab_branch_bank')
                ->routeName('admin.base-information.banks.location' ?? 'admin.base-information.banks.locations.edit')
                ->routeParameters(['bank' => encrypt($bankId)])
                ->title('بانک')
                ->subTitle('لیست شعبات بانک')
                ->permission('admin.base-information.banks.location' ?? 'admin.base-information.banks.locations.edit'),

//            Tab::make('tab_subject_category')
//                ->routeName('admin.books.subject-categories.show')
//                ->routeParameters(['subject_category' => encrypt($firstSubjectCategoryId)])
//                ->title(__('basemodule::field.subject_category'))
//                ->subTitle(__('basemodule::section.tab.book_subject_category'))
//                ->badge(numberFormatter($subjectCategoriesCount))
//                ->permission('admin.books.subject-categories.show'),
//
//            Tab::make('tab_subject_content')
//                ->routeName($subjectContentsCount ? 'admin.books.subject-contents.show' : 'admin.books.subject-contents.index')
//                ->routeParameters(['subject_content' => encryptValue($firstSubjectContentId)])
//                ->title(__('basemodule::field.content'))
//                ->subTitle(__('basemodule::section.content_info'))
//                ->badge(numberFormatter($subjectContentsCount))
//                ->permission('admin.books.subject-contents.show')
//                ->disabled($subjectContentsCount === 0)
//                ->disabledTooltip(__('book::message.no_content_exist')),
        ];
    }
}
