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

    public function store(Request $request)
    {
        $this->validate($request, $this->getEntity()->access_rules($request));

        $data = $this->getService()->store($request->only($this->getEntity()->access_rules_attributes()));

        $this->created($data);
    }
}
