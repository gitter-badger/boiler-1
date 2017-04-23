<?php

namespace Yakuzan\Boiler\Policies;

use Yakuzan\Boiler\Entities\User;
use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractPolicy
{
    use EntityTrait;

    /**
     * @param User  $user
     * @param array ...$args
     *
     * @return bool
     */
    public function view(User $user, ...$args)
    {
        return $user->can('view_'.strtolower($this->entity_base_name()));
    }

    /**
     * @param User  $user
     * @param array ...$args
     *
     * @return bool
     */
    public function store(User $user, ...$args)
    {
        return $user->can('store_'.strtolower($this->entity_base_name()));
    }

    /**
     * @param User  $user
     * @param array ...$args
     *
     * @return bool
     */
    public function update(User $user, ...$args)
    {
        return $user->can('update_'.strtolower($this->entity_base_name()));
    }

    /**
     * @param User  $user
     * @param array ...$args
     *
     * @return bool
     */
    public function destroy(User $user, ...$args)
    {
        return $user->can('destroy_'.strtolower($this->entity_base_name()));
    }
}
