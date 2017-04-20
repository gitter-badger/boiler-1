<?php

namespace Yakuzan\Boiler\Controllers;

use Illuminate\Http\Request;
use Yakuzan\Boiler\Services\ServiceInterface;

interface ControllerInterface
{
    public function index();

    public function create();

    /**
     * @param Request $request
     */
    public function store(Request $request);

    /**
     * @param int $id
     */
    public function show(int $id);

    /**
     * @param int $id
     */
    public function edit(int $id);

    /**
     * @param Request $request
     * @param int     $id
     */
    public function update(Request $request, int $id);

    /**
     * @param int $id
     */
    public function destroy(int $id);

    /**
     * @return ServiceInterface
     */
    public function getService(): ServiceInterface;
}
