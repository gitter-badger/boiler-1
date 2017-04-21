<?php

namespace Yakuzan\Boiler\Tests\Stubs\Transformers;

use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Transformers\AbstractTransformer;

class LessonTransformer extends AbstractTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Lesson $lesson)
    {
        return [
            'id'      => $lesson->id,
            'titre'   => $lesson->title,
            'matiere' => $lesson->subject,
        ];
    }
}
