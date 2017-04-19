<?php

namespace Yakuzan\Boiler\Controllers;

use Illuminate\Http\Response;

abstract class AbstractWebController extends AbstractController
{
    /**
     * @param string|null $view
     * @param array $data
     * @param array $mergeData
     * @return Response
     */
    abstract public function getView(string $view = null, array $data = [], array $mergeData = []): Response;
}
