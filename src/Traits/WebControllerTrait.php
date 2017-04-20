<?php

namespace Yakuzan\Boiler\Traits;

use Illuminate\Http\Response;

trait WebControllerTrait
{
    use ControllerTrait;

    protected $dataTable = null;

    /** @var Response */
    protected $view = null;

    public function getDataTable()
    {
        return $this->dataTable;
    }

    public function setDataTable($dataTable)
    {
        $this->dataTable = $dataTable;
    }

    /**
     * @param string|null $view
     * @param array       $data
     * @param array       $mergeData
     *
     * @return \Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function getView(string $view = null, array $data = [], array $mergeData = [])
    {
        if (null !== $this->view) {
            return $this->view;
        }

        $folder = strtolower(preg_replace('/App\\\/', '', $this->entity));

        if (view()->exists($folder.'.'.$view)) {
            return view($folder.'.'.$view, $data, $mergeData);
        }
    }

    public function setView($view)
    {
        $this->view = $view;
    }
}
