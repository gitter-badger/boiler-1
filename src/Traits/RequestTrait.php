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

        if (is_a($this->request, AbstractRequest::class, true)) {
            return new $this->request();
        }
    }
}
