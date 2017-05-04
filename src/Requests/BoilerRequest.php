<?php

namespace Yakuzan\Boiler\Requests;

use Illuminate\Foundation\Http\FormRequest;
use function strtolower;
use Yakuzan\Boiler\Controllers\AbstractController;
use Yakuzan\Boiler\Traits\EntityTrait;
use Yakuzan\Boiler\Traits\ResponseTrait;

class BoilerRequest extends FormRequest
{
    use EntityTrait, ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return empty($this->route())
            || !is_a($this->route()->getController(), AbstractController::class)
            || !in_array($this->route()->getActionMethod(), $this->route()->getController()->blacklist(), true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $map = ['store'  => 'access_rules', 'update' => 'modify_rules'];

        if (!empty($route = $this->route())
            && array_key_exists($method = $route->getActionMethod(), $map)
            && is_a($controller = $route->getController(), AbstractController::class)
        ) {
            return $controller->service()->entity()->{$map[$method]}($this);
        }

        $this->startSession();

        return [];
    }

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
            return $this->invalidRequest('The given data failed to pass validation.', $errors);
        }

        return parent::response($errors);
    }

    private function startSession()
    {
        if (!empty($route = $this->route()) && is_a($controller = $route->getController(), AbstractController::class)) {
            $this->session()->put('uri', $route->uri());
        }
    }
}
