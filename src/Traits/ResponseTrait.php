<?php

namespace Yakuzan\Boiler\Traits;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait ResponseTrait
{
    protected $statusCode = Response::HTTP_OK;

    /**
     * @param string|array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data)
    {
        return response()->json($data, $this->statusCode);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = 'Error')
    {
        return $this->respond([
            'error' => [
                'message'     => $message,
                'status_code' => $this->statusCode,
            ]
        ]);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithMessage($message = 'OK')
    {
        return $this->respond([
            'response' => [
                'message'     => $message,
                'status_code' => $this->statusCode,
            ]
        ]);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination(LengthAwarePaginator $paginator, array $data = [])
    {

        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginator->total(),
                'total_pages'  => ceil($paginator->total() / $paginator->perPage()),
                'current_page' => $paginator->currentPage(),
                'limit'        => $paginator->perPage(),
            ],
        ]);

        return $this->respond($data);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($message = 'Not Found!')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidRequest($message = 'Invalid Request!')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError($message = 'Internal Error')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    public function created($message = 'Created')
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithMessage($message);
    }

    public function accepted($message = 'Accepted')
    {
        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respondWithMessage($message);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
