<?php
namespace App\Component\Table\Item;

use App\Component\Table\Exceptions\ParseException;
use App\Component\Table\TableColumn;
use App\Component\Traits\Error;

abstract class AbstractItem
{
    use Error;

    protected $columnName;
    protected $column;
    protected $options;
    protected $type;
    protected $label;
    protected $class;
    protected $parameter;
    protected $item;

    public function __construct(TableColumn $column, array $options)
    {
        $this->column = $column;
        $this->columnName = $column->getName();
        $this->options = $options;
        $this->init();
        $this->parse();
    }

    public function init(): void
    {
        $this->setType();
    }

    public function parse(): void
    {
        $this->parseParameter();
        $this->parseClass();
        $this->parseLabel();
    }

    abstract protected function setType(): void;
    abstract public function parseLabel(): void;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return TableColumn
     */
    public function getColumn(): TableColumn
    {
        return $this->column;
    }

    /**
     * @param TableColumn $column
     */
    public function setColumn(TableColumn $column)
    {
        $this->column = $column;
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
     * @return mixed
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param mixed $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    protected function parseParameter(): void
    {
        if (isset($this->options['parameter'])) {
            if (!is_string($this->options['parameter'])) {
                throw new ParseException("Column name: {$this->column->getName()}. Item parameter should be of type string!");
            } elseif (empty($this->options['parameter'])) {
                throw new ParseException("Column name: {$this->column->getName()}. Item parameter should be of type string!");
            }

            $this->parameter = $this->options['parameter'];
        }
    }

    protected function parseClass(): void
    {
        if (isset($this->options['class'])) {
            if (!is_string($this->options['class'])) {
                throw new ParseException("Column name: {$this->column->getName()}. Item class should be of type string!");
            } elseif (empty($this->options['label'])) {
                throw new ParseException("Column name: {$this->column->getName()}. Item class should be of type string!");
            }

            $this->class = $this->options['class'];
        }
    }
}