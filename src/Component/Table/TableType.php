<?php

namespace App\Component\Table;

class TableType
{
    const TABLE = 'table';
    const TABLE_ID = 'id';
    const TABLE_COLUMNS = 'columns';

    const TYPE_NAME = 'name';
    const TYPE_TYPE = 'type';
    const TYPE_OPTIONS = 'options';

    const TYPE_LABEL = 'label';
    const TYPE_TEXT = 'text';
    const TYPE_CLASS = 'class';
    const TYPE_URL = 'url';
    const TYPE_ROUTE = 'route';
    const TYPE_PARAM = 'param';
    const TYPE_SORTABLE = 'sortable';
    const TYPE_PARAMETER = 'parameter';
    const TYPE_FORMAT = 'format';
    const TYPE_ACTIONS = 'actions';
    const TYPE_PARSE = 'parse';
    const TYPE_VALUE = 'value';
    const TYPE_NUMBER = 'number';
    const TYPE_STRING = 'string';
    const TYPE_OBJECT = 'object';
    const TYPE_MIXED = 'mixed';
    const TYPE_ACTION = 'action';

    const TYPE_COLUMN = [
        self::TYPE_NAME,
        self::TYPE_TYPE,
        self::TYPE_OPTIONS
    ];

    /**
     * Columns Types
     */
    const TYPES = [
        self::TYPE_NUMBER,
        self::TYPE_STRING,
        self::TYPE_OBJECT,
        self::TYPE_PARSE,
        self::TYPE_MIXED,
        self::TYPE_ACTION
    ];

    /**
     * Actions types
     */
    const ACTION_VIEW = 'view';
    const ACTION_EDIT = 'edit';
    const ACTION_DELETE = 'delete';

    const ACTIONS = [
        self::ACTION_VIEW,
        self::ACTION_EDIT,
        self::ACTION_DELETE
    ];

    const ACTIONS_CLASS = [
        self::ACTION_VIEW => 'btn btn-sm btn-info',
        self::ACTION_EDIT => 'btn btn-sm btn-primary',
        self::ACTION_DELETE => 'btn btn-sm btn-warning'
    ];

    const ACTION_CLASS_DEFAULT = 'btn btn-sm btn-secondary';

    const OPTION_TYPES = [
        self::TYPE_NUMBER => [
            self::TYPE_LABEL,
            self::TYPE_SORTABLE,
            self::TYPE_CLASS,
        ],
        self::TYPE_STRING => [
            self::TYPE_LABEL,
            self::TYPE_SORTABLE,
            self::TYPE_CLASS,
        ],
        self::TYPE_OBJECT => [
            self::TYPE_LABEL,
            self::TYPE_SORTABLE,
            self::TYPE_PARAMETER,
            self::TYPE_FORMAT,
            self::TYPE_CLASS,
        ],
        self::TYPE_PARSE => [
            self::TYPE_LABEL,
            self::TYPE_SORTABLE,
            self::TYPE_CLASS,
            self::TYPE_PARSE,
        ],
        self::TYPE_MIXED => [
            self::TYPE_LABEL,
            self::TYPE_SORTABLE,
            self::TYPE_CLASS,
            self::TYPE_VALUE,
        ],
        self::TYPE_ACTION => [
            self::TYPE_LABEL,
            self::TYPE_ACTIONS,
        ],
    ];


    const VALUE_OPTION_URL = 'url';

    const VALUE_OPTIONS = [
        self::TYPE_URL => [
            self::TYPE_LABEL,
            self::TYPE_PARAMETER,
            self::TYPE_CLASS,
            self::TYPE_ROUTE,
            self::TYPE_PARAM,
        ]
    ];

    const ACTION_OPTIONS = [
        self::TYPE_LABEL,
        self::TYPE_CLASS,
        self::TYPE_ROUTE,
        self::TYPE_PARAM
    ];

    /**
     * Pagination Types
     */
    const PAGINATION_DETAILS_UP_LEFT    = 'details_up_left';
    const PAGINATION_DETAILS_UP_RIGHT   = 'details_up_right';
    const PAGINATION_DETAILS_DOWN_LEFT  = 'details_down_left';
    const PAGINATION_DETAILS_DOWN_RIGHT = 'details_down_right';
    const PAGINATION_ITEMS_UP_LEFT      = 'items_up_left';
    const PAGINATION_ITEMS_UP_RIGHT     = 'items_up_right';
    const PAGINATION_ITEMS_DOWN_LEFT    = 'items_down_left';
    const PAGINATION_ITEMS_DOWN_RIGHT   = 'items_down_right';

    const PAGINATION_TYPES = [
        self::PAGINATION_DETAILS_UP_LEFT,
        self::PAGINATION_DETAILS_UP_RIGHT,
        self::PAGINATION_DETAILS_DOWN_LEFT,
        self::PAGINATION_DETAILS_DOWN_RIGHT,
        self::PAGINATION_ITEMS_UP_LEFT,
        self::PAGINATION_ITEMS_UP_RIGHT,
        self::PAGINATION_ITEMS_DOWN_LEFT,
        self::PAGINATION_ITEMS_DOWN_RIGHT,
    ];

    const PAGINATION_OPTION_ITEMS = 'items';

    /**
     * Pagination pages types
     */
    const PAGINATION_ITEM_FIRST = 'first';
    const PAGINATION_ITEM_PREVIOUS = 'previous';
    const PAGINATION_ITEM_PAGES = 'pages';
    const PAGINATION_ITEM_NEXT = 'next';
    const PAGINATION_ITEM_LAST = 'last';

    const PAGINATION_ITEMS = [
        self::PAGINATION_ITEM_FIRST,
        self::PAGINATION_ITEM_PREVIOUS,
        self::PAGINATION_ITEM_PAGES,
        self::PAGINATION_ITEM_NEXT,
        self::PAGINATION_ITEM_LAST,
    ];

    const UP = 'up';
    const DOWN = 'down';
    const LEFT = 'left';
    const RIGHT = 'right';
    const ITEMS = 'items';
    const DETAILS = 'details';

    /**
     * Form types
     */
    const FORM_TYPE_SEARCH = 'search';
    public const FORM_TYPE_TABLE = 'table';

    const FORM_TYPES = [
        self::FORM_TYPE_SEARCH,
        self::FORM_TYPE_TABLE
    ];

    /**
     * Form options
     */
    const FORM_OPTION_ACTION = 'action';
    const FORM_OPTION_METHOD = 'method';

    const FORM_OPTIONS = [
        self::FORM_OPTION_ACTION,
        self::FORM_OPTION_METHOD
    ];

    /**
     * Form methods
     */
    const FORM_METHOD_POST = 'post';
    const FORM_METHOD_GET = 'get';

    const FORM_METHODS = [
        self::FORM_METHOD_POST,
        self::FORM_METHOD_GET
    ];

    /**
     * Form options for form types - required
     */
    const FORM_TYPE_OPTIONS = [
        self::FORM_TYPE_SEARCH => [],
        self::FORM_TYPE_TABLE => [
            self::FORM_OPTION_ACTION,
            self::FORM_OPTION_METHOD
        ]
    ];
}