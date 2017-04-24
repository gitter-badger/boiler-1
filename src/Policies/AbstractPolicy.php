<?php

namespace Yakuzan\Boiler\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Yakuzan\Boiler\Entities\AbstractEntity;
use Yakuzan\Boiler\Entities\User;
use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractPolicy
{
    use EntityTrait, HandlesAuthorization;

    /**
     * @param User           $user
     * @param AbstractEntity $entity
     *
     * @return bool
     */
    public function view(User $user, AbstractEntity $entity = null)
    {
        return $user->hasPermission('view_'.strtolower($this->entity_base_name()));
    }

    /**
     * @param User           $user
     * @param AbstractEntity $entity
     *
     * @return bool
     */
    public function create(User $user, AbstractEntity $entity = null)
    {
        return $user->hasPermission('create_'.strtolower($this->entity_base_name()));
    }

    /**
     * @param User           $user
     * @param AbstractEntity $entity
     *
     * @return bool
     */
    public function update(User $user, AbstractEntity $entity = null)
    {
        return $user->hasPermission('update_'.strtolower($this->entity_base_name()));
    }

    /**
     * @param User           $user
     * @param AbstractEntity $entity
     *
     * @return bool
     */
    public function delete(User $user, AbstractEntity $entity = null)
    {
        return $user->hasPermission('delete_'.strtolower($this->entity_base_name()));
    }
}
