<?php

namespace Yakuzan\Boiler\Traits;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait ResponseTrait
{
    protected $data = [];

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
     * @param null   $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = null, $data = null)
    {
        $error = [
            'message'     => $message ?? Response::$statusTexts[$this->statusCode] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (null !== $data) {
            $error['data'] = $data;
        }

        return $this->respond(['error' => $error]);
    }

    /**
     * @param string $message
     * @param null   $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithMessage($message = null, $data = null)
    {
        $response = [
            'message'     => $message ?? Response::$statusTexts[$this->statusCode] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (null !== $data) {
            $response['data'] = $data;
        }

        return $this->respond(['response' => $response]);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @param array                $data
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
    public function notFound($message = null)
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     * @param null   $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidRequest($message = null, $data = null)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message, $data);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError($message = null)
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    public function created($message = null, $data = null)
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithMessage($message, $data);
    }

    public function accepted($message = null)
    {
        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respondWithMessage($message);
    }

    public function noContent($message = null)
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respondWithMessage($message);
    }

    public function unauthorized($message = null)
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message);
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
