<?php

namespace App\Component\Table\Item;

use App\Component\Table\Exceptions\ParseException;

class SingleItem extends AbstractItem
{
    const TYPE = 'single';

    public function setType(): void
    {
        $this->type = self::TYPE;
    }

    public function parseLabel(): void
    {
        if (empty($this->parameter)) {
            throw new ParseException("Column name: {$this->column->getName()}. Required item parameter!");
        }

        $this->label = "@@{$this->parameter}@@";
    }

    public function parse(): void
    {
        parent::parse();

        $this->item = [
            'type' => self::TYPE,
            'label' => $this->label
        ];
    }
}