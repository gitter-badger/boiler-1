<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Transformers\AbstractTransformer;

trait TransformerTrait
{
    /** @var AbstractTransformer */
    protected $transformer;

    /**
     * @param null $transformer
     *
     * @return $this|AbstractTransformer
     */
    public function transformer($transformer = null)
    {
        if (null !== $transformer) {
            $this->transformer = $transformer;

            return $this;
        }

        if ($this->transformer instanceof AbstractTransformer) {
            return $this->transformer;
        }

        if (is_string($this->transformer) && class_exists($this->transformer)) {
            return new $this->transformer();
        }
    }
}
