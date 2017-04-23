<?php

namespace Yakuzan\Boiler\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\JsonApiSerializer;
use Yakuzan\Boiler\Entities\AbstractEntity;
use Yakuzan\Boiler\Traits\ResponseTrait;
use Yakuzan\Boiler\Traits\TransformerTrait;

abstract class AbstractApiController extends AbstractController
{
    use TransformerTrait, ResponseTrait;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $paginator = $this->service()->paginate(
                $request->input('limit', null),
                $request->input('columns', ['*']),
                $request->input('pageName', 'page'),
                $request->input('page', null)
            );
        } catch(AuthorizationException $exception) {
            return $this->unauthorized();
        }

        $collection = $paginator->getCollection();

        if (0 === $collection->count()) {
            return $this->notFound();
        }

        $data = fractal($collection, $this->transformer())
            ->serializeWith(new JsonApiSerializer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();

        return $this->respond($data);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $entity = $this->service()->find($id);
        } catch(AuthorizationException $exception) {
            return $this->unauthorized();
        }

        if (null === $entity) {
            return $this->notFound();
        }

        $data = fractal($entity, $this->transformer())->toArray();

        return $this->respond($data);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), $this->service()->entity()->access_rules($request));

        if ($validator->fails()) {
            return $this->invalidRequest('The given data failed to pass validation.', $validator->getMessageBag()->toArray());
        }

        $attributes = $request->only($this->service()->entity()->access_attributes());

        try {
            $entity = $this->service()->create($attributes);
        } catch(AuthorizationException $exception) {
            return $this->unauthorized();
        }

        if ($entity instanceof AbstractEntity) {
            $data = fractal($entity, $this->transformer())->toArray();

            return $this->created(null, $data['data']);
        }

        return $this->internalError();
    }

    public function update(Request $request, $id)
    {
        $validator = validator($request->all(), $this->service()->entity()->modify_rules($request));

        if ($validator->fails()) {
            return $this->invalidRequest('The given data failed to pass validation.', $validator->getMessageBag()->toArray());
        }

        try {

            $entity = $this->service()->find($id);

            if (null === $entity) {
                return $this->notFound();
            }

            $attributes = $request->only($this->service()->entity()->modify_attributes());

            $result = $this->service()->entity($entity)->update($attributes);

        } catch(AuthorizationException $exception) {
            return $this->unauthorized();
        }

        if (true === $result) {
            return $this->accepted();
        }

        return $this->internalError();
    }

    public function destroy($id)
    {
        try {
            $entity = $this->service()->find($id);

            if (null === $entity) {
                return $this->notFound();
            }

            $result = $this->service()->entity($entity)->delete();
        } catch(AuthorizationException $exception) {
            return $this->unauthorized();
        }

        if (true === $result) {
            return $this->noContent();
        }

        return $this->internalError();
    }
}
