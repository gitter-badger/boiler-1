<?php

namespace Yakuzan\Boiler\Services;

use Yakuzan\Boiler\Entities\AbstractEntity;
use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractService
{
    use EntityTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->entity()->all();
    }

    /**
     * @param int $id
     *
     * @return AbstractEntity
     */
    public function find(int $id)
    {
        return $this->entity()->find($id);
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes = [])
    {
        return $this->entity()->create($attributes);
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public function update(array $values)
    {
        return $this->entity()->update($values);
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        return $this->entity()->delete();
    }
}
