<?php

namespace Yakuzan\Boiler\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class AbstractRequest extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse([
                'error' => [
                    'errors'      => $errors,
                    'status_code' => 422,
                ],
            ], 422);
        }

        return parent::response($errors);
    }
}
