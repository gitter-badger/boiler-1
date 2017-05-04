<?php

namespace Yakuzan\Boiler\Traits;

trait ViewTrait
{
    /** @var string */
    protected $view;

    /**
     * @param string|null $view
     *
     * @return string|ViewTrait
     */
    public function view($view = null)
    {
        if (null !== $view) {
            $this->view = $view;

            return $this;
        }

        return $this->view;
    }
}
