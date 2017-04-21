<?php

namespace Yakuzan\Boiler\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class AbstractEntity extends Model
{
    /** @var array */
    protected $access_rules;

    /** @var array */
    protected $modify_rules;

    /** @var array */
    protected $access_attributes;

    /** @var array */
    protected $modify_attributes;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function access_rules(Request $request): array
    {
        return $this->access_rules ?? [];
    }

    /**
     * @param array $rules
     *
     * @return AbstractEntity
     */
    public function set_access_rules(array $rules): AbstractEntity
    {
        $this->access_rules = $rules;

        return $this;
    }

    /**
     * @param array $rules
     *
     * @return AbstractEntity
     */
    public function set_modify_rules(array $rules): AbstractEntity
    {
        $this->access_rules = $rules;

        return $this;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function modify_rules(Request $request): array
    {
        if (null !== $this->modify_rules) {
            return $this->modify_rules;
        }

        return $this->access_rules($request);
    }

    /**
     * @param array|null $access_attributes
     *
     * @return array
     */
    public function access_attributes(array $access_attributes = null): array
    {
        if (null !== $access_attributes) {
            $this->access_attributes = $access_attributes;
        }

        if (null !== $this->access_attributes) {
            return $this->access_attributes;
        }

        return $this->fillable;
    }

    /**
     * @param array|null $modify_attributes
     *
     * @return array
     */
    public function modify_attributes(array $modify_attributes = null): array
    {
        if (null !== $modify_attributes) {
            $this->modify_attributes = $modify_attributes;
        }

        if (null !== $this->modify_attributes) {
            return $this->modify_attributes;
        }

        return $this->access_attributes();
    }
}
