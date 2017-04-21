<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Entities\AbstractEntity;
use Yakuzan\Boiler\Requests\AbstractRequest;
use Yakuzan\Boiler\Services\AbstractService;
use Yakuzan\Boiler\Transformers\AbstractTransformer;

trait ControllerTrait
{
    /** @var  AbstractService */
    protected $service;

    /** @var  AbstractEntity */
    protected $entity;

    /** @var  AbstractTransformer */
    protected $transformer;

    /** @var AbstractRequest */
    protected $request;

    /**
     * @return null|AbstractService
     */
    public function getService()
    {
        if ($this->service instanceof AbstractService) {
            return $this->service;
        }

        if (is_string($this->service) && class_exists($this->service)) {
            return new $this->service();
        }

        return null;
    }

    /**
     * @param $service
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }


    /**
     * @return null|AbstractEntity
     */
    public function getEntity()
    {
        if ($this->entity instanceof AbstractEntity) {
            return $this->entity;
        }

        if (is_string($this->entity) && class_exists($this->entity)) {
            return new $this->entity();
        }

        return null;
    }

    /**
     * @param AbstractEntity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return null|AbstractTransformer
     */
    public function getTransformer()
    {
        if ($this->transformer instanceof AbstractTransformer) {
            return $this->transformer;
        }

        if (is_string($this->transformer) && class_exists($this->transformer)) {
            return new $this->transformer();
        }

        return null;
    }

    /**
     * @param AbstractTransformer $transformer
     * @return $this
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }

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
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }
}
