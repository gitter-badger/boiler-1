<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Transformers\AbstractTransformer;

trait TransformerTrait
{
    /** @var AbstractTransformer */
    protected $transformer;

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
     *
     * @return $this
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }
}
