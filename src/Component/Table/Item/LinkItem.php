<?php

namespace App\Component\Table\Item;

use App\Component\Table\Exceptions\ParseException;
use App\Component\Table\Item\Traits\Map;
use App\Component\Table\Item\Traits\Routing;

class LinkItem extends AbstractItem
{
    use Map, Routing;

    const TYPE = 'link';

    public function init(): void
    {
        parent::init();

        $table = $this->column->getTable();

        $this->setRouter($table->getRouter());
    }

    public function setType(): void
    {
        $this->type = self::TYPE;
    }

    public function parseLabel(): void
    {
        if (!empty($this->options['label'])) {
            if (!is_string($this->options['label'])) {
                throw new ParseException("Column name: {$this->columnName}. Item label should be of type string!");
            }
            $this->label = $this->options['label'];
        } elseif (!empty($this->options['parameter'])) {
            if (!is_string($this->options['parameter'])) {
                throw new ParseException("Column name: {$this->columnName}. Item parameter should be of type string!");
            }
            $label = $this->options['parameter'];
            if (!empty($this->map)) {
                $label .= '|m';
            }
            $this->label = "@@{$label}@@";
        } else {
            throw new ParseException("Column name: {$this->columnName}. Item required parameter or label!");
        }
    }

    public function parseRoute(): void
    {
        if (!empty($this->options['route'])) {
            if (!is_string($this->options['route'])) {
                throw new ParseException("Column name: {$this->columnName}. Item route should be of type string!");
            }
            $this->route = $this->options['route'];
        } else {
            throw new ParseException("Column name: {$this->columnName}. Item required route!");
        }
    }

    public function parse(): void
    {
        $this->checkMap();

        parent::parse();

        $this->parseRoute();

        $this->param = [];
        if (isset($this->options['param'])) {
            $this->setParam($this->options['param']);
        }

        $this->generateUrl();

        $this->item = [
            'type' => self::TYPE,
            'label' => $this->label,
            'url' => $this->url
        ];
        if (!empty($this->class)) {
            $this->item['class'] = $this->class;
        }
        if (!empty($this->map)) {
            $this->item['map'] = $this->map;
        }
    }

}