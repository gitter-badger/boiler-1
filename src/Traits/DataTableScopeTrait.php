<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\DataTables\AbstractDataTableScope;
use Yakuzan\Boiler\DataTables\DefaultDataTableScope;

trait DataTableScopeTrait
{
    /** @var  string */
    protected $data_table_scope;

    /**
     * @param $data_table_scope
     * @return string|AbstractDataTableScope
     */
    public function data_table_scope($data_table_scope = null)
    {
        if (null !== $data_table_scope) {
            $this->data_table_scope = $data_table_scope;

            return $this;
        }

        if ($this->data_table_scope instanceof AbstractDataTableScope) {
            return $this->data_table_scope;
        }

        if (is_a($this->data_table_scope, AbstractDataTableScope::class, true)) {
            return new $this->data_table_scope();
        }

        return $this->guessScopeFromEntityName();
    }

    /**
     * @return DefaultDataTableScope|ServiceTrait
     */
    private function guessScopeFromEntityName()
    {
        if ('' !== $entity = $this->entity_base_name()) {
            $data_table_scope = config('boiler.datatable_scope_namespace').'\\'.$entity.'DataTableScope';
            if (class_exists($data_table_scope)) {
                $this->data_table_scope = $data_table_scope;

                return new $data_table_scope();
            }

            return new DefaultDataTableScope();
        }
    }

    /**
     * @return string
     */
    abstract public function entity_base_name();
}
