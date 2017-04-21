<?php

$factory(\Yakuzan\Boiler\Tests\Stubs\Entities\Lesson::class, [
    'title'   => $faker->sentence,
    'subject' => $faker->words(2),
]);
