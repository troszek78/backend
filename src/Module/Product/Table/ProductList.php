<?php

namespace App\Module\Product\Table;

use App\Component\Table\Interfaces\ListInterface;
use App\Component\Table\Interfaces\TableBuilderInterface;
use App\Component\Table\Item\DateItem;
use App\Component\Table\TableType as TP;
use App\Module\Product\Form\ProductSearchForm;

class ProductList implements ListInterface
{
    public function buildTable(TableBuilderInterface $builder, array $options) : void
    {
        $builder
            ->addForm(ProductSearchForm::class, TP::FORM_TYPE_SEARCH)
            ->addTableId('product-list')
            ->addColumn('id', [
                'label' => 'Id',
                'sortable' => true,
            ])
            ->addColumn('name', [
                'label' => 'Name',
                'sortable' => true
            ])
            ->addColumn('info', [
                'label' => 'Info',
                'sortable' => true
            ])
            ->addColumn('public_date', [
                'label' => 'Public Date',
                'sortable' => true,
                'items' => [
                    [
                        'type' => DateItem::class,
                        'parameter' => 'public_date',
                        'format' => 'm/d/Y'
                    ]
                ]
            ])->addColumn('actions', [
                'label' => 'Actions',
                'actions' => [
                    TP::ACTION_VIEW,
                    TP::ACTION_EDIT,
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
                        TP::PAGINATION_ITEM_FIRST,
                        TP::PAGINATION_ITEM_PREVIOUS,
                        TP::PAGINATION_ITEM_PAGES => '3',
                        TP::PAGINATION_ITEM_NEXT,
                        TP::PAGINATION_ITEM_LAST
                    ],
                ]
            );
    }
}