<?php

namespace Yakuzan\Boiler\Traits;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait ResponseTrait
{
    protected $data = [];

    protected $statusCode = Response::HTTP_OK;

    /**
     * @param string|array|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data)
    {
        return response()->json($data, $this->statusCode);
    }

    /**
     * @param string|null $message
     * @param array|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = null, $data = null)
    {
        $error = [
            'message'     => $message ?? Response::$statusTexts[ $this->statusCode ] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (null !== $data) {
            $error[ 'data' ] = $data;
        }

        return $this->respond(['error' => $error]);
    }

    /**
     * @param string|null $message
     * @param array|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithMessage($message = null, $data = null)
    {
        $response = [
            'message'     => $message ?? Response::$statusTexts[ $this->statusCode ] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (null !== $data) {
            $response[ 'data' ] = $data;
        }

        return $this->respond(['response' => $response]);
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
     * @param string|null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($message = null)
    {
        return $this->status_code(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string|null $message
     * @param array|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidRequest($message = null, $data = null)
    {
        return $this->status_code(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message, $data);
    }

    /**
     * @param string|null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError($message = null)
    {
        return $this->status_code(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param string|null $message
     * @param array|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($message = null, $data = null)
    {
        return $this->status_code(Response::HTTP_CREATED)->respondWithMessage($message, $data);
    }

    /**
     * @param string|null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accepted($message = null)
    {
        return $this->status_code(Response::HTTP_ACCEPTED)->respondWithMessage($message);
    }

    /**
     * @param string|null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent($message = null)
    {
        return $this->status_code(Response::HTTP_NO_CONTENT)->respondWithMessage($message);
    }

    /**
     * @param string|null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized($message = null)
    {
        return $this->status_code(Response::HTTP_UNAUTHORIZED)->respondWithError($message);
    }

    /**
     * @param int|null $statusCode
     *
     * @return int|ResponseTrait
     */
    public function status_code($statusCode = null)
    {
        if (null !== $statusCode) {
            $this->statusCode = $statusCode;

            return $this;
        }

        return $this->statusCode;
    }
}
