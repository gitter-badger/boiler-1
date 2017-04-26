<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Transformers\AbstractTransformer;
use Yakuzan\Boiler\Transformers\DefaultTransformer;

trait TransformerTrait
{
    /** @var AbstractTransformer */
    protected $transformer;

    /**
     * @param null $transformer
     *
     * @return TransformerTrait|AbstractTransformer
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

        return $this->guessFromEntityName();
    }

    /**
     * @return AbstractTransformer|ServiceTrait
     */
    private function guessFromEntityName()
    {
        if ('' !== $entity = $this->entity_base_name()) {
            $transformer = config('boiler.transformers_namespace').'\\'.$entity.'Transformer';
            if (class_exists($transformer)) {
                $this->transformer = $transformer;

                return new $transformer();
            }

            return new DefaultTransformer();
        }
    }
}
