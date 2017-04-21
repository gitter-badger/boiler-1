<?php

namespace Yakuzan\Boiler\Controllers;

use App\Http\Controllers\Controller;
use Yakuzan\Boiler\Services\AbstractService;

abstract class AbstractController extends Controller
{
    abstract public function getService(): AbstractService;
}
