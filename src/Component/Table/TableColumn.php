<?php

namespace App\Component\Table;

use App\Component\Table\Exceptions\ParseException;
use App\Component\Table\Item\AbstractItem;
use App\Component\Table\Item\LinkItem;
use App\Component\Traits\Converts;
use App\Component\Traits\Error;
use App\Component\Table\TableType as TP;

class TableColumn
{
    // traits
    use Converts, Error;

    private $table;
    private $name;
    private $options = [];
    private $label;
    private $sortable = false;
    private $parameter;
    private $class;
    private $items = [];

    public function __construct(TableBuilder $table, string $name, array $options)
    {
        $this->table = $table;
        $this->setName($name);
        $this->setOptions($options);
    }

    /**
     * @return TableBuilder
     */
    public function getTable(): TableBuilder
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getColumn(): array
    {
        $column = [];
        $column['name'] = $this->name;
        $column['label'] = $this->label;
        if ($this->sortable) {
            $column['sortable'] = $this->sortable;
            $column['parameter'] = $this->parameter;
        }
        if (!empty($this->class)) {
            $column['class'] = $this->class;
        }
        $column['items'] = $this->items;
        $column['json'] = json_encode($column, JSON_FORCE_OBJECT,10);

        return $column;
    }

    /**
     * parse
     */
    public function parse(): void
    {
        try {
            $this->parseLabel();
            $this->parseSortable();
            $this->parseParameter();
            $this->parseClass();
            $this->parseActions();
            $this->parseItems();
        } catch (ParseException $exception) {
            $this->addError($exception->getMessage());
        }
    }

    /**
     * Parse Label
     * @throws ParseException
     */
    private function parseLabel(): void
    {
        if (isset($this->options['label'])) {
            if (!is_string($this->options['label'])) {
                throw new ParseException("Column name: {$this->name}. Label should be of type string!");
            } elseif (empty($this->options['label'])) {
                throw new ParseException("Column name: {$this->name}. Label should be of type string!");
            }

            $this->label = $this->options['label'];
        } else {
            $this->label = self::snakeToLabel($this->name);
        }
    }

    /**
     * Parse Sortable
     * @throws ParseException
     */
    private function parseSortable(): void
    {
        if (isset($this->options['sortable'])) {
            if (is_bool($this->options['sortable'])) {
                $this->sortable = $this->options['sortable'];
            } else {
                throw new ParseException("Column name: {$this->name}. Sortable should be of type bool!");
            }
        }
    }

    /**
     * Parse Parameter
     * @throws ParseException
     */
    private function parseParameter(): void
    {
        if (isset($this->options['parameter'])) {
            if (!is_string($this->options['parameter'])) {
                throw new ParseException("Column name: {$this->name}. Parameter should be of type string!");
            } elseif (empty($this->options['label'])) {
                throw new ParseException("Column name: {$this->name}. Parameter should be of type string!");
            }

            $this->parameter = $this->options['parameter'];
        } else {
            $this->parameter = $this->name;
        }
    }

    /**
     * parse Class
     * @throws ParseException
     */
    private function parseClass(): void
    {
        if (isset($this->options['class'])) {
            if (!is_string($this->options['class'])) {
                throw new ParseException("Column name: {$this->name}. Class should be of type string!");
            } elseif (empty($this->options['class'])) {
                throw new ParseException("Column name: {$this->name}. Class should be of type string!");
            }

            $this->addClass($this->options['class']);
        }
    }

    /**
     * Add Class
     * @param string $class
     */
    private function addClass(string $class): void
    {
        $class = trim($class);

        if (empty($class)) {
            return;
        }

        if (!empty($this->class)) {
            $this->class = trim($this->class);
        }

        if (empty($this->class)) {
            $this->class = $class;
        } else {
            $this->class = $this->class . ' ' . $class;
        }
    }

    /**
     * Parse Actions
     */
    private function parseActions(): void
    {
        if (empty($this->options['actions'])) {
            return;
        }

        $this->addClass('text-right');
        $route_base = LinkItem::getBaseRoute($this->table->getControllerName());

        foreach ($this->options['actions'] as $actionKey => $actionValue) {
            $item = [
                'type' => LinkItem::class
            ];

            if (is_string($actionValue)) {
                $item['route'] = $route_base . "_" . $actionValue;
                $item['label'] = ucfirst($actionValue);
                $item['param'] = 'id';
                if (isset(TP::ACTIONS_CLASS[$actionValue])) {
                    $item['class'] = TP::ACTIONS_CLASS[$actionValue];
                } else {
                    $item['class'] = TP::ACTION_CLASS_DEFAULT;
                }
            } elseif (is_array($actionValue)) {
                if (isset($actionValue['route'])) {
                    $item['route'] = $actionValue['route'];
                } else {
                    $item['route'] = $route_base . '_' . $actionKey;
                }

                if (isset($actionValue['label'])) {
                    $item['label'] = $actionValue['label'];
                } else {
                    $item['label'] = ucfirst($actionKey);
                }

                if (isset($actionValue['class'])) {
                    $item['class'] = $actionValue['class'];
                } elseif (isset(TP::ACTIONS_CLASS[$actionKey])) {
                    $item['class'] = TP::ACTIONS_CLASS[$actionKey];
                } else {
                    $item['class'] = TP::ACTION_CLASS_DEFAULT;
                }

                if (isset($actionValue['param'])) {
                    $item['param'] = $actionValue['param'];
                } else {
                    $item['param'] = 'id';
                }
            }

            $this->options['items'][] = $item;
        }
    }

    /**
     * Parse Items
     * @throws ParseException
     */
    private function parseItems(): void
    {
        if (empty($this->options['items'])) {
            $this->items[] = [
                'type' => 'single',
                'label' => "@@{$this->parameter}@@"
            ];
        } elseif (!is_array($this->options['items'])) {
            throw new ParseException("Column name: {$this->name}. Items should be of type array!");
        } else {
            foreach ($this->options['items'] as $itemToParse) {
                if (empty($itemToParse['type'])) {
                    throw new ParseException("Column name: {$this->name}. Unknown item type!");
                }
                $type = $itemToParse['type'];
                $item = new $type($this, $itemToParse);

                // check instance
                if (!$item instanceof AbstractItem) {
                    throw new ParseException("Column name: {$this->name}. Item should be a BasicItem instance!");
                }

                // check item errors
                if ($item->hasErrors()) {
                    $this->addErrors($item->getErrors());
                } else {
                    $this->items[] = $item->getItem();
                }
            }
        }
    }

}