<?php

namespace App\Component\Table\Interfaces;

interface ListInterface
{
    /**
     * @param TableBuilderInterface $builder
     * @param array $options
     */
    public function buildTable(TableBuilderInterface $builder, array $options): void;
}