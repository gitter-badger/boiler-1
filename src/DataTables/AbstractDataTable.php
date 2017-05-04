<?php

namespace Yakuzan\Boiler\DataTables;

use Illuminate\Support\Facades\Schema;
use Yajra\Datatables\Services\DataTable;
use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractDataTable extends DataTable
{
    use EntityTrait;

    protected $columns;

    protected $datatable_action = 'abstract.action';

    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', $this->datatable_action);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->entity()->query()->select($this->getColumns());

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->addAction(['width' => '80px'])
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        if(null !== $this->columns) {
            return $this->columns;
        }

        return array_diff(Schema::getColumnListing($this->entity()->getTable()), $this->entity()->getHidden());
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return str_plural(strtolower($this->entity_base_name())).'datatable_' . time();
    }
}
