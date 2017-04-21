<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Requests\AbstractRequest;

trait RequestTrait
{
    /** @var AbstractRequest */
    protected $request;

    /**
     * @return null|AbstractRequest
     */
    public function getRequest()
    {
        if ($this->request instanceof AbstractRequest) {
            return $this->request;
        }

        if (is_string($this->request) && class_exists($this->request)) {
            return new $this->request();
        }

        return null;
    }

    /**
     * @param AbstractRequest $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }
}
