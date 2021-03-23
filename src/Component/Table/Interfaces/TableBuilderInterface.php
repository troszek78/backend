<?php

namespace App\Component\Table\Interfaces;


interface TableBuilderInterface
{
    /**
     * @param string $name
     * @return TableBuilderInterface
     */
    public function addTableId(string $name): TableBuilderInterface;

    /**
     * @param string $name
     * @param array $options
     * @return TableBuilderInterface
     */
    public function addColumn(string $name, array $options = []): TableBuilderInterface;

    /**
     * @param string $fullClassName
     * @param string $type
     * @param array $options
     * @return TableBuilderInterface
     */
    public function addForm(string $fullClassName, string $type, array $options = []): TableBuilderInterface;

    /**
     * @param array $pagination_format
     * @param array $options
     * @return TableBuilderInterface
     */
    public function addPagination(array $pagination_format, array $options = []): TableBuilderInterface;


}