<?php

namespace App\Component\Table\Item;

use App\Component\Table\Exceptions\ParseException;
use App\Component\Table\Item\Traits\Map;

class MapItem extends AbstractItem
{
    use Map;

    const TYPE = 'map';

    public function setType(): void
    {
        $this->type = self::TYPE;
    }

    public function parseLabel(): void
    {
        if (empty($this->parameter)) {
            throw new ParseException("Column name: {$this->column->getName()}. Required item parameter!");
        }

        $this->label = "@@{$this->parameter}|m@@";
    }

    public function parse(): void
    {
        $this->checkRequiredMap();

        parent::parse();

        $this->item = [
            'type' => self::TYPE,
            'label' => $this->label,
            'map' => $this->map
        ];
    }

}