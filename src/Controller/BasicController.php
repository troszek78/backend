<?php

namespace App\Controller;

use App\Component\Table\Interfaces\TableInterface;
use App\Component\Table\TableBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BasicController extends AbstractController
{
    const ROUTE_BASE = null;

    /**
     * @param string $type
     * @param array $options
     * @return TableInterface
     */
    public function createTable(string $type, $options = []) : TableInterface
    {
        $table = new TableBuilder($this->container->get('router'), get_class($this));
        $list = new $type;

        return $table->create($list , $options);
    }

}
