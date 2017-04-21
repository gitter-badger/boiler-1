<?php

namespace Yakuzan\Boiler\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
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
