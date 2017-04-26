<?php

namespace Yakuzan\Boiler\Transformers;

use Illuminate\Database\Eloquent\Model;

class DefaultTransformer extends AbstractTransformer
{
    /**
     * A Fractal transformer.
     *
     * @param Model $entity
     *
     * @return array
     */
    public function transform(Model $entity)
    {
        return $entity->toArray();
    }
}
