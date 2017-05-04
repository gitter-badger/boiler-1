<?php

namespace Yakuzan\Boiler\DataTables;

use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;

class DefaultDataTable extends AbstractDataTable
{
    /**
     * DefautDataTable constructor.
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param $entity
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, $entity)
    {
        parent::__construct($datatables, $viewFactory);

        $this->entity($entity);
    }
}
