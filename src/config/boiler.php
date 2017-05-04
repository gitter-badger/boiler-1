<?php

return [
    'entities_namespace'           => 'App',
    'controllers_namespace'        => 'App\\Http\\Controllers',
    'transformers_namespace'       => 'App\\Transformers',
    'services_namespace'           => 'App\\Services',
    'policies_namespace'           => 'App\\Policies',
    'views_path'                   => resource_path('views'),
    'datatable_namespace'          => 'App\\DataTables',
    'datatable_scope_namespace'    => 'App\\DataTables\\Scopes',
    'exceptions'                   => [
        \Illuminate\Auth\Access\AuthorizationException::class       => ['method' => 'unauthorized', 'message' => null],
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => ['method' => 'notFound', 'message' => null],
        \Illuminate\Auth\AuthenticationException::class             => ['method'  => 'unauthorized', 'message' => 'Unauthenticated'],
    ],
];
