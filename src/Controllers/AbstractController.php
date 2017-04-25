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
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ServiceTrait, EntityTrait, RequestTrait;

    protected $blacklist = [];

    /**
     * @param array|null $blacklist
     *
     * @return array|AbstractController
     */
    public function blacklist(array $blacklist = null)
    {
        if (null !== $blacklist) {
            $this->blacklist = $blacklist;

            return $this;
        }

        return $this->blacklist;
    }
}
