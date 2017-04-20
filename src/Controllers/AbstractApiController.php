<?php

namespace Yakuzan\Boiler\Controllers;

use Yakuzan\Boiler\Traits\ResponseTrait;
use Yakuzan\Boiler\Traits\WebControllerTrait;

abstract class AbstractApiController extends AbstractController
{
    use WebControllerTrait;
    use ResponseTrait;

    public function index()
    {
        $data = $this->getService()->index();

        $this->respondWithPagination($data);
    }
}
