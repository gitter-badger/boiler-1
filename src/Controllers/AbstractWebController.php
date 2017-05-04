<?php

namespace Yakuzan\Boiler\Controllers;

use Yakuzan\Boiler\Requests\BoilerRequest;
use Yakuzan\Boiler\Traits\DataTableScopeTrait;
use Yakuzan\Boiler\Traits\DataTableTrait;
use Yakuzan\Boiler\Traits\ViewTrait;

abstract class AbstractWebController extends AbstractController
{
    use ViewTrait, DataTableTrait, DataTableScopeTrait;

    public function index(BoilerRequest $request)
    {
        return $this->data_table()->addScope($this->data_table_scope())->render('abstract.index');
    }
}
