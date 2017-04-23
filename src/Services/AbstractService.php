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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return AbstractEntity
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return bool
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(array $values)
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('update', $this->entity());
        }

        return $this->entity()->update($values);
    }

    /**
     * @return bool|null
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('delete', $this->entity());
        }

        return $this->entity()->delete();
    }

    /**
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        if (is_a($this->policy, AbstractPolicy::class, true)) {
            $this->authorize('view', $this->entity_name());
        }

        return $this->entity()->paginate($perPage, $columns, $pageName, $page);
    }
}
