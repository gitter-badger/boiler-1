<?php

namespace Yakuzan\Boiler\DataTables;

class DefaultDataTableScope extends AbstractDataTableScope
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     *
     * @return mixed
     */
    public function apply($query)
    {
        // return $query->where('id', 1);
    }
}
