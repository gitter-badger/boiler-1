<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Requests\AbstractRequest;

trait RequestTrait
{
    /** @var AbstractRequest */
    protected $request;

    /**
     * @param null $request
     *
     * @return AbstractRequest|$this
     */
    public function request($request = null)
    {
        if (null !== $request) {
            $this->request = $request;

            return $this;
        }
        if ($this->request instanceof AbstractRequest) {
            return $this->request;
        }

        if (is_string($this->request) && class_exists($this->request)) {
            return new $this->request();
        }
    }
}
