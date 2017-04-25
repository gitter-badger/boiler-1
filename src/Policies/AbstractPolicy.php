<?php

namespace Yakuzan\Boiler\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Yakuzan\Boiler\Entities\User;
use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractPolicy
{
    use EntityTrait, HandlesAuthorization;

    /**
     * @param $name
     * @param $arguments
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        $user = $arguments[0] instanceof User ? $arguments[0] : null;

        return $this->authorize($name, $user);
    }

    /**
     * @param string $function
     * @param User   $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    private function authorize(string $function, User $user): bool
    {
        if (false === $user->hasPermission($function.'_'.strtolower($this->entity_base_name()))) {
            throw new \Illuminate\Auth\Access\AuthorizationException('This action is unauthorized.', 401);
        }

        return true;
    }
}
