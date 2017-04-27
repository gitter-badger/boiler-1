<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Requests\BoilerRequest;

trait RequestTrait
{
    /** @var BoilerRequest */
    protected $request;

    /**
     * @param null $request
     *
     * @return BoilerRequest|RequestTrait
     */
    public function request($request = null)
    {
        if (null !== $request) {
            $this->request = $request;

            return $this;
        }

        if ($this->request instanceof BoilerRequest) {
            return $this->request;
        }

        if (is_a($this->request, BoilerRequest::class, true)) {
            return new $this->request();
        }
    }
}
