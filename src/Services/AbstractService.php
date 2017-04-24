<?php

namespace Yakuzan\Boiler\Services;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Yakuzan\Boiler\Entities\AbstractEntity;
use Yakuzan\Boiler\Policies\AbstractPolicy;
use Yakuzan\Boiler\Traits\EntityTrait;
use Yakuzan\Boiler\Traits\PolicyTrait;

abstract class AbstractService
{
    use EntityTrait, PolicyTrait, AuthorizesRequests;

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('view', $this->entity_name());
        }

        return $this->entity()->all();
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AbstractEntity
     */
    public function find($id)
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('view', $this->entity_name());
        }

        return $this->entity()->find($id);
    }

    /**
     * @param array $attributes
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function create(array $attributes = [])
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('create', $this->entity_name());
        }

        return $this->entity()->create($attributes);
    }

    /**
     * @param array $values
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    public function update(array $values)
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('update', $this->entity());
        }

        return $this->entity()->update($values);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool|null
     */
    public function delete()
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('delete', $this->entity());
        }

        return $this->entity()->delete();
    }

    /**
     * @param null   $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('view', $this->entity_name());
        }

        return $this->entity()->paginate($perPage, $columns, $pageName, $page);
    }
}
