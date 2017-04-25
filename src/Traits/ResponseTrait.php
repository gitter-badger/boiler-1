<?php

namespace Yakuzan\Boiler\Traits;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ResponseTrait
{
    protected $statusCode = Response::HTTP_OK;

    /**
     * @param string|array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array  $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = null, $data = [], array $headers = [])
    {
        $error = [
            'message'     => $message ?? Response::$statusTexts[$this->statusCode] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (null !== $data) {
            $error['data'] = $data;
        }

        return $this->respond(['error' => $error], $headers);
    }

    /**
     * @param string|null $message
     * @param string|array  $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithMessage($message = null, $data = [], array $headers = [])
    {
        $response = [
            'message'     => $message ?? Response::$statusTexts[$this->statusCode] ?? '',
            'status_code' => $this->statusCode,
        ];

        if (null !== $data) {
            $response['data'] = $data;
        }

        return $this->respond(['response' => $response], $headers);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @param array                $data
     * @param array                $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination(LengthAwarePaginator $paginator, array $data = [], array $headers = [])
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginator->total(),
                'total_pages'  => ceil($paginator->total() / $paginator->perPage()),
                'current_page' => $paginator->currentPage(),
                'limit'        => $paginator->perPage(),
            ],
        ]);

        return $this->respond($data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_NOT_FOUND)->respondWithError($message, $data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array  $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidRequest($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message, $data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message, $data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array  $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_CREATED)->respondWithMessage($message, $data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accepted($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_ACCEPTED)->respondWithMessage($message, $data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_NO_CONTENT)->respondWithMessage($message, $data, $headers);
    }

    /**
     * @param string|null $message
     * @param string|array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized($message = null, $data = [], array $headers = [])
    {
        return $this->status_code(Response::HTTP_UNAUTHORIZED)->respondWithError($message, $data, $headers);
    }

    /**
     * Create a file download response. Wrapper for Response::download()
     *
     * @param  \SplFileInfo|string  $file
     * @param  string  $name
     * @param  array   $headers
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file, $name = null, array $headers = [])
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, 'attachment');

        if ($name !== null) {
            return $response->setContentDisposition('attachment', $name, Str::ascii($name));
        }

        return $response;
    }

    /**
     * Create a streamed response. Wrapper for Response::stream()
     *
     * @param  callable $callback
     * @param  integer  $status
     * @param  array    $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function stream(callable $callback, $status = 200, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
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
