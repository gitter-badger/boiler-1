<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Policies\AbstractPolicy;

trait PolicyTrait
{
    /** @var AbstractPolicy */
    protected $policy;

    /**
     * @param null $policy
     *
     * @return AbstractPolicy|PolicyTrait
     */
    public function policy($policy = null)
    {
        if (null !== $policy) {
            $this->policy = $policy;

            return $this;
        }

        if ($this->policy instanceof AbstractPolicy) {
            return $this->policy;
        }

        if (is_a($this->policy, AbstractPolicy::class, true)) {
            return new $this->policy();
        }

        return $this->guessFromEntityName();
    }

    /**
     * @return AbstractPolicy|ServiceTrait
     */
    private function guessFromEntityName()
    {
        if ('' !== $entity = $this->entity_base_name()) {
            $policy = config('boiler.policies_namespace').'\\'.$entity.'Policy';
            if (class_exists($policy)) {
                $this->policy = $policy;

                return new $policy();
            }
        }
    }
}
