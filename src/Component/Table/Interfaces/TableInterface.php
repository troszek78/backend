<?php

namespace App\Component\Table\Interfaces;

interface TableInterface
{
    /**
     * create
     * @param ListInterface $list
     * @param array $options
     * @return TableInterface
     */
    public function create(ListInterface $list, array $options): TableInterface;

    /**
     * @return string
     */
    public function getTableSettings(): string;
}