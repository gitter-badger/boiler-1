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

        if (is_a($this->transformer, AbstractTransformer::class, true)) {
            return new $this->transformer();
        }
    }
}
