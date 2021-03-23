<?php

namespace App\Module\PersonLikeProduct\Table;

use App\Component\Table\Interfaces\ListInterface;
use App\Component\Table\Interfaces\TableBuilderInterface;
use App\Component\Table\Item\LinkItem;
use App\Module\PersonLikeProduct\Form\PersonForm;
use App\Component\Table\TableType as TP;

class ProductList implements ListInterface
{
    public function buildTable(TableBuilderInterface $builder, array $options) : void
    {
        $builder
            ->addForm(PersonForm::class, TP::FORM_TYPE_SEARCH)
            ->addTableId('person-like-product-full-list')
            ->addColumn('product', [
                'label' => 'Product',
                'sortable' => true,
                'items' => [
                    [
                        'type' => LinkItem::class,
                        'parameter' => 'product_name',
                        'route' => 'product_view',
                        'param' => [
                            'id' => 'product_id'
                        ]
                    ]
                ],
            ])->addColumn('actions', [
                'label' => 'Delete',
                'actions' => [
                    TP::ACTION_DELETE => [
                        TP::TYPE_LABEL => 'Delete',
                        'param' => [
                            'person_id',
                            'product_id',
                        ]
                    ],
                ]
            ])->addPagination(
                [
                    TP::PAGINATION_DETAILS_UP_RIGHT,
                    TP::PAGINATION_ITEMS_UP_LEFT,
                    TP::PAGINATION_DETAILS_DOWN_RIGHT,
                    TP::PAGINATION_ITEMS_DOWN_LEFT,
                ],
                [
                    TP::PAGINATION_OPTION_ITEMS => [
                        TP::PAGINATION_ITEM_PREVIOUS,
                        TP::PAGINATION_ITEM_NEXT,
                    ],
                ]
            );
    }
}