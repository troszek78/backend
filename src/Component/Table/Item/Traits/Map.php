<?php
namespace App\Component\Table\Item\Traits;

use App\Component\Table\Exceptions\ParseException;

trait Map
{
    private $map;

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @param array map
     */
    public function setMap(array $map): void
    {
        $this->map = $map;
    }

    public function checkRequiredMap(): void
    {
        if (empty($this->options['map'])) {
            throw new ParseException("1:Column name: {$this->columnName}. Item map should be of type array!");
        } elseif (!is_array($this->options['map'])) {
            throw new ParseException("2:Column name: {$this->columnName}. Item map should be of type array!");
        } else {
            foreach ($this->options['map'] as $value) {
                if (!is_string($value)) {
                    throw new ParseException("Column name: {$this->columnName}. Item map item should be of type string!");
                }
            }
        }

        $this->map = $this->options['map'];
    }

    public function checkMap(): void
    {
        if (isset($this->options['map'])) {
            if (!is_array($this->options['map'])) {
                throw new ParseException("2:Column name: {$this->columnName}. Item map should be of type array!");
            } else {
                foreach ($this->options['map'] as $value) {
                    if (!is_string($value)) {
                        throw new ParseException("Column name: {$this->columnName}. Item map item should be of type string!");
                    }
                }
            }

            $this->map = $this->options['map'];
        }
    }
}
