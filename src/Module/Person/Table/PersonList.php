<?php

namespace App\Module\Person\Table;

use App\Component\Table\Item\MapItem;
use App\Entity\Person;
use App\Module\Person\Form\PersonSearchForm;
use App\Component\Table\Interfaces\ListInterface;
use App\Component\Table\Interfaces\TableBuilderInterface;
use App\Component\Table\TableType as TP;

class PersonList implements ListInterface
{
    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addForm(PersonSearchForm::class, TP::FORM_TYPE_SEARCH)
            ->addTableId('person-list')
            ->addColumn('id', [
                'label' => 'Id',
                'sortable' => true,
            ])
            ->addColumn('login', [
                'label' => 'Login',
                'sortable' => true
            ])
            ->addColumn('l_name',  [
                'label' => 'Last Name',
                'sortable' => true
            ])
            ->addColumn('f_name', [
                'label' => 'First Name',
                'sortable' => true
            ])
            ->addColumn('state', [
                'label' => 'State',
                'sortable' => true,
                'items' => [
                    [
                        'type' => MapItem::class,
                        'parameter' => 'state',
                        'map' => Person::STATE_LABELS
                    ]
                ]
            ])->addColumn('actions', [
                'label' => 'Actions',
                'actions' => [
                    'like' => [
                        TP::TYPE_LABEL => 'Like',
                        TP::TYPE_ROUTE => 'like_person',
                    ],
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