<?php

namespace App\Component\Table;

use App\Component\Table\Exceptions\TableException;
use App\Component\Table\Interfaces\ListInterface;
use App\Component\Table\Interfaces\TableBuilderInterface;
use App\Component\Table\Interfaces\TableInterface;
use App\Component\Table\TableType as TP;
use App\Component\Traits\Converts;
use App\Component\Traits\Error;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TableBuilder implements TableBuilderInterface, TableInterface
{
    /**
     * Traits
     */
    use Converts, Error;

    /**
     * parameters
     */

    /**
     * @var string
     */
    private $tableId;

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var array
     */
    private $form = [];

    /**
     * @var array
     */
    private $pagination = [];

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * TableBuilder constructor.
     * @param UrlGeneratorInterface $router
     * @param string $controllerName
     */
    public function __construct(UrlGeneratorInterface $router, string $controllerName)
    {
        $this->router = $router;
        $this->controllerName = $controllerName;
    }

    /**
     * @return string
     */
    public function getTableId(): string
    {
        return $this->tableId;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getForm(): array
    {
        return $this->form;
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }

    /**
     * @return string
     */
    public function getSerializedPaginationItems(): string
    {
        if (isset($this->pagination['items'])) {
            return json_encode($this->pagination['items'],
                JSON_FORCE_OBJECT,
                10
            );
        }

        return '';
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getRouter(): UrlGeneratorInterface
    {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @param ListInterface $list
     * @param array $options
     * @return TableInterface
     */
    public function create(ListInterface $list, array $options): TableInterface
    {
        $list->buildTable($this, $options);

        return $this;
    }

    /**
     * @return string
     */
    public function getTableSettings(): string
    {
        return json_encode($this->getArrayTableSettings(),
            JSON_FORCE_OBJECT,
            10
        );
    }

    /**
     * @return array
     */
    public function getArrayTableSettings() :array
    {
        return [
            'form' => $this->form,
            'table' => [
                'id' => $this->tableId,
                'columns' => $this->columns
            ],
            'pagination' => $this->pagination,
            'errors' => $this->errors
        ];
    }

    /**
     * @param string $name
     * @return TableBuilderInterface $this
     */
    public function addTableId(string $name): TableBuilderInterface
    {
        $this->tableId = $name;

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return TableBuilderInterface $this
     *
     * @throws TableException
     */
    public function addColumn(string $name, array $options = []): TableBuilderInterface
    {
        // check column
        if (isset($this->columns[$name])) {
            throw new TableException("Column name: {$name} exists.");
        }
        $column = new TableColumn($this, $name, $options);
        // parse column
        $column->parse();

        // check parse errors
        if ($column->hasErrors()) {
            // add errors
            $this->addErrors($column->getErrors());
        } else {
            // set columns
            $this->columns[$column->getName()] = $column->getColumn();
        }

        return $this;
    }

    /**
     * @param string $fullClassName
     * @param string $type
     * @param array $options
     * @return TableBuilderInterface $this
     */
    public function addForm(string $fullClassName, string $type, array $options = []): TableBuilderInterface
    {
        try {
            $formId = self::classNameToSnake($fullClassName);

            $form['form_id'] = $formId;

            // chek form type
            if (in_array($type, TP::FORM_TYPES)) {
                $form['type'] = $type;
            } else {
                throw new TableException("Wrong form type: {$type}!");
            }

            // add options
            if ($this->addOptionsToForm($form, $options)) {
                $this->form = $form;
            }
        }  catch(TableException $exception) {
            // add error
            $this->addError($exception->getMessage());
        }

        return $this;
    }

    /**
     * @param array $pagination_format
     * @param array $options
     * @return TableBuilderInterface $this
     */
    public function addPagination(array $pagination_format, array $options = []): TableBuilderInterface
    {
        try {
            // check form
            if (empty($this->form)) {
                throw new TableException("To add pagination you need to set the form!");
            }
            $items = false;
            foreach ($pagination_format as $value) {
                switch ($value) {
                    case TP::PAGINATION_ITEMS_UP_LEFT:
                        $this->pagination[TP::UP][TP::LEFT][] = TP::ITEMS;
                        $items = true;
                        break;
                    case TP::PAGINATION_ITEMS_UP_RIGHT:
                        $this->pagination[TP::UP][TP::RIGHT][] = TP::ITEMS;
                        $items = true;
                        break;
                    case TP::PAGINATION_ITEMS_DOWN_LEFT:
                        $this->pagination[TP::DOWN][TP::LEFT][] = TP::ITEMS;
                        $items = true;
                        break;
                    case TP::PAGINATION_ITEMS_DOWN_RIGHT:
                        $this->pagination[TP::DOWN][TP::RIGHT][] = TP::ITEMS;
                        $items = true;
                        break;
                    case TP::PAGINATION_DETAILS_UP_LEFT:
                        $this->pagination[TP::UP][TP::LEFT][] = TP::DETAILS;
                        break;
                    case TP::PAGINATION_DETAILS_UP_RIGHT:
                        $this->pagination[TP::UP][TP::RIGHT][] = TP::DETAILS;
                        break;
                    case TP::PAGINATION_DETAILS_DOWN_LEFT:
                        $this->pagination[TP::DOWN][TP::LEFT][] = TP::DETAILS;
                        break;
                    case TP::PAGINATION_DETAILS_DOWN_RIGHT:
                        $this->pagination[TP::DOWN][TP::RIGHT][] = TP::DETAILS;
                        break;
                    default:
                        throw new TableException("Wrong pagination detail type: {$value}!");
                }
            }

            if ($items && empty($options[TP::PAGINATION_OPTION_ITEMS])) {
                throw new TableException("Missing pagination options for items!");
            }

            foreach (TP::PAGINATION_ITEMS as $item) {
                if ($item === TP::PAGINATION_ITEM_PAGES) {
                    if (isset($options[TP::PAGINATION_OPTION_ITEMS][$item])) {
                        $value = $options[TP::PAGINATION_OPTION_ITEMS][$item];
                        if (is_numeric($value)) {
                            $this->pagination[TP::ITEMS][$item] = (int)$value;
                        } else {
                            throw new TableException(
                                "Wrong pagination items option 'pages' value: {$value} - should be number!"
                            );
                        }
                    }
                } elseif (in_array($item, $options[TP::PAGINATION_OPTION_ITEMS])) {
                    $this->pagination[TP::ITEMS][$item] = true;
                }
            }

        }  catch(TableException $exception) {
            $this->addError($exception->getMessage());
        }

        return $this;
    }

    /**
     * @param $form
     * @param $options
     * @return bool
     */
    private function addOptionsToForm(&$form, $options) : bool
    {
        $result = true;
        foreach (TP::FORM_TYPE_OPTIONS[$form['type']] as $requiredOption) {
            if (in_array($requiredOption, array_keys($options))) {
                if (!$this->addOptionToForm($form, $requiredOption, $options[$requiredOption])) {
                    $result = false;
                }
            } else {
                $this->addError(
                    "Missing table option: {$requiredOption} for form type: {$form['type']}!"
                );
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @param $form
     * @param $name
     * @param $option
     * @return bool
     */
    private function addOptionToForm(&$form, $name, $option) : bool
    {
        try {
            switch ($name) {
                case TP::FORM_OPTION_ACTION:
                    $form['options'][$name] = $option;
                    break;
                case TP::FORM_OPTION_METHOD:
                    if (in_array($option, TP::FORM_METHODS)) {
                        $form['options'][$name] = $option;
                    } else {
                        throw new TableException(
                            "Wrong form method: {$option}!"
                        );
                    }
                    break;
            }
        }  catch(TableException $exception) {
            $this->addError($exception->getMessage());
            return false;
        }

        return true;
    }
}
