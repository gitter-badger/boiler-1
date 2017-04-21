<?php

namespace Yakuzan\Boiler\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Yakuzan\Boiler\Traits\EntityTrait;
use Yakuzan\Boiler\Traits\RequestTrait;
use Yakuzan\Boiler\Traits\ServiceTrait;

abstract class AbstractController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use ServiceTrait;
    use EntityTrait;
    use RequestTrait;
}
