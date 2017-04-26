<?php

namespace Yakuzan\Boiler\Tests\Stubs\Transformers;

use Yakuzan\Boiler\Tests\Stubs\Entities\Guess;
use Yakuzan\Boiler\Transformers\AbstractTransformer;

class GuessTransformer extends AbstractTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Guess $guess)
    {
        return [
            'id'      => (int) $guess->id,
            'titre'   => $guess->title,
        ];
    }
}
