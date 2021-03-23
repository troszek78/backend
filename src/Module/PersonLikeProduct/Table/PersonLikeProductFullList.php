<?php

namespace App\Module\PersonLikeProduct\Table;

use App\Component\Table\Item\LinkItem;
use App\Module\PersonLikeProduct\Form\SearchForm;
use App\Component\Table\Interfaces\ListInterface;
use App\Component\Table\Interfaces\TableBuilderInterface;
use App\Component\Table\TableType as TP;

class PersonLikeProductFullList implements ListInterface
{
    public function buildTable(TableBuilderInterface $builder, array $options) : void
    {
        $builder
            ->addForm(SearchForm::class, TP::FORM_TYPE_SEARCH)
            ->addTableId('person-like-product-full-list')
            ->addColumn('person', [
                'label' => 'Person',
                'sortable' => true,
                'items' => [
                    [
                        'type' => LinkItem::class,
                        'parameter' => 'person_name',
                        'route' => 'person_edit',
                        'param' => [
                            'id' => 'person_id'
                        ]
                    ],
                    [
                        'type' => LinkItem::class,
                        'label' => 'Like',
                        'route' => 'like_person',
                        'param' => [
                            'id' => 'person_id'
                        ]
                    ],
                ]
            ])
            ->addColumn('product',  [
                'label' => 'Product',
                'sortable' => true,
                'parameter' => 'product_id',
                'items' => [
                    [
                        'type' => LinkItem::class,
                        'parameter' => 'product_name',
                        'route' => 'product_edit',
                        'param' => [
                            'id' => 'product_id'
                        ],
                    ]
                ],
            ])
            ->addColumn('delete', [
                'label' => 'Delete',
                'actions' => [
                    TP::ACTION_DELETE => [
                        'param' => [
                            'person_id',
                            'product_id',
                        ]
                    ],
                ]
            ])
            ->addPagination(
                [
                    TP::PAGINATION_DETAILS_UP_RIGHT,
                    TP::PAGINATION_ITEMS_UP_LEFT,
                    TP::PAGINATION_DETAILS_DOWN_RIGHT,
                    TP::PAGINATION_ITEMS_DOWN_LEFT,
                ],
                [
                    TP::PAGINATION_OPTION_ITEMS => [
                        TP::PAGINATION_ITEM_FIRST,
                        TP::PAGINATION_ITEM_PREVIOUS,
                        TP::PAGINATION_ITEM_NEXT,
                    ],
                ]
            );
    }
}
