<?php

namespace Yakuzan\Boiler\Traits;

use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yakuzan\Boiler\DataTables\AbstractDataTable;
use Yakuzan\Boiler\DataTables\DefaultDataTable;

trait DataTableTrait
{
    /** @var  string */
    protected $data_table;

    /**
     * @param $data_table
     * @return string|AbstractDataTable
     */
    public function data_table($data_table = null)
    {
        if (null !== $data_table) {
            $this->data_table = $data_table;

            return $this;
        }

        if ($this->data_table instanceof AbstractDataTable) {
            return $this->data_table;
        }

        if (is_a($this->data_table, AbstractDataTable::class, true)) {
            return new $this->data_table(app(Datatables::class), app(Factory::class));
        }

        return $this->guessFromEntityName();
    }

    /**
     * @return DefaultDataTable|AbstractPolicy|ServiceTrait
     */
    private function guessFromEntityName()
    {
        if ('' !== $entity = $this->entity_base_name()) {
            $data_table = config('boiler.datatable_namespace').'\\'.$entity.'DataTable';
            if (class_exists($data_table)) {
                $this->data_table = $data_table;

                $default = new $data_table(app(Datatables::class), app(Factory::class));

                $default->entity($this->entity());

                return $default;
            }

            return new DefaultDataTable(app(Datatables::class), app(Factory::class), $this->entity());
        }
    }

    /**
     * @return string
     */
    abstract public function entity_base_name();

    /**
     * @param string|AbstractEntity|null $entity
     *
     * @return AbstractEntity|$this
     */
    abstract public function entity($entity = null);
}
