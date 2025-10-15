<?php

namespace Modules\Bank\Generators\Filters;

use Dornica\PanelKit\Generator\Filter\BaseFilter;
use Dornica\PanelKit\Generator\Filter\Builders\Filter;
use Modules\Location\Enums\Service;

class BankFilter extends BaseFilter
{
    /**
     * Define the filters for the panel.
     *
     * Each filter should be created using the Filter builder and returned in an array.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            Filter::make("service")
                ->placeholder('خدمات')
//                ->only(["tab_subject_category"])
                ->items(
                    prepareSelectComponentData(
                        source: Service::class,
                        moduleName: "location",
                    )
                ),
//            Filter::make("address")
//                ->placeholder('آدرس')
//                ->items(prepareSelectComponentData(
//                    source: $this->subjectCategories,
//                    labelColumn: 'title',
//                    shouldEncryptValue: true,
//                ))
//                ->dependOnRoute('admin.b', [
//                    'book' => encrypt($this->book->id),
//                    'subject_category' => encrypt($this->book->subjectCategories->first()->id)
//                ])
////                ->only(["tab_subject_category"])
//                ->dependOnParentID('type'),
        ];
    }

    public function defaults(): array
    {
        return [
            'service' => $this->service,
//            'subject_category' => $this->selectedSubjectCategory,
        ];
    }

}
