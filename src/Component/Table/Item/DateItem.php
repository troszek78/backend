<?php

namespace App\Component\Table\Item;

use App\Component\Table\Exceptions\ParseException;

class DateItem extends AbstractItem
{
    const TYPE = 'date';

    private $format;

    public function setType(): void
    {
        $this->type = self::TYPE;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function parseLabel(): void
    {
        if (empty($this->parameter)) {
            throw new ParseException("Column name: {$this->column->getName()}. Required item parameter!");
        }

        $this->label = "@@{$this->parameter}|f_{$this->format}@@";
    }

    protected function parseFormat()
    {
        if (empty($this->options['format'])) {
            throw new ParseException("Column name: {$this->column->getName()}. Required item format!");
        }

        $this->format = $this->options['format'];
    }

    public function parse(): void
    {
        $this->parseFormat();

        parent::parse();

        $this->item = [
            'type' => self::TYPE,
            'label' => $this->label
        ];
    }

}