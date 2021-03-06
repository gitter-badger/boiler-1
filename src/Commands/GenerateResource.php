<?php

namespace Yakuzan\Boiler\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateResource extends Command
{
    protected $signature = 'make:resource {resource : name of the resource in singular}';

    protected $description = 'Generate base scaffolding for a resource';

    protected $files;

    private $composer;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = app('composer');
    }

    public function handle()
    {
        $this->info('Generating files');

        $resource = $this->getEntityName();

        $this->generateClass('entity', config('boiler.entities_namespace'), $resource);
        $this->generateClass('service', config('boiler.services_namespace'), $resource);
        $this->generateClass('transformer', config('boiler.transformers_namespace'), $resource);
        $this->generateClass('policy', config('boiler.policies_namespace'), $resource);
        $this->generateClass('controller', config('boiler.controllers_namespace'), $resource);

        $this->info('please wait...');

        $this->composer->dumpAutoloads();
    }

    protected function generateClass($type, $namespace, $entity)
    {
        $path = $this->getPath($this->qualifyClass($namespace.'\\'.$entity.('entity' === $type ? '' : ucfirst($type))));
        $basePath = str_replace($this->laravel->basePath().'/', '', $path);

        if ($this->files->exists($path)) {
            $this->error($basePath.' already exists!');

            return;
        }

        $stub = $this->getCompiledStub($type, $namespace, $entity);

        $this->makeDir(str_replace(basename($path), '', $path));

        $this->files->put($path, $stub);

        $this->info($basePath.' Created successfully');
    }

    protected function qualifyClass($name)
    {
        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    protected function getPath($name)
    {
        $name = str_replace_first($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    protected function makeDir($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
    }

    protected function getEntityName()
    {
        return ucwords(str_singular(camel_case($this->argument('resource'))));
    }

    protected function getArguments()
    {
        return [
            ['resource', InputArgument::REQUIRED, 'The name of the resource'],
        ];
    }

    protected function getCompiledStub($type, $namespace, $entity)
    {
        $stub = $this->files->get(__DIR__.'/../stubs/'.$type.'.stub');

        return strtr($stub, [
            '{{namespace}}'              => $namespace,
            '{{entity}}'                 => $entity,
            '{{entities_namespace}}'     => config('boiler.entities_namespace'),
            '{{controllers_namespace}}'  => config('boiler.controllers_namespace'),
            '{{services_namespace}}'     => config('boiler.services_namespace'),
            '{{transformers_namespace}}' => config('boiler.transformers_namespace'),
            '{{policies_namespace}}'     => config('boiler.policies_namespace'),
        ]);
    }
}
