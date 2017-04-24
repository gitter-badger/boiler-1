<?php

$factory(\Yakuzan\Boiler\Tests\Stubs\Entities\Lesson::class, [
    'title'   => $faker->sentence,
    'subject' => $faker->words(2),
]);

$factory(\Yakuzan\Boiler\Tests\Stubs\Entities\User::class, [
    'name'           => $faker->name,
    'email'          => $faker->unique()->safeEmail,
    'password'       => bcrypt('secret'),
    'remember_token' => str_random(10),
]);

$factory(\Yakuzan\Boiler\Tests\Stubs\Entities\Role::class, [
    'name'         => $faker->unique()->name,
    'display_name' => $faker->name,
    'description'  => $faker->sentence,
]);

$factory(\Yakuzan\Boiler\Tests\Stubs\Entities\Permission::class, [
    'name'         => $faker->unique()->name,
    'display_name' => $faker->name,
    'description'  => $faker->sentence,
]);
