<?php

namespace Yakuzan\Boiler\Controllers;

use function fractal;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\JsonApiSerializer;
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
        $paginator = $this->service()->paginate(
            $request->input('limit', null),
            $request->input('columns', ['*']),
            $request->input('pageName', 'page'),
            $request->input('page', null)
        );

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
        $entity = $this->service()->find($id);

        if (null === $entity) {
            return $this->notFound();
        }

        $data = fractal($entity, $this->transformer())->toArray();

        return $this->respond($data);
    }
}
