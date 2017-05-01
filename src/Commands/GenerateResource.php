<?php

namespace Yakuzan\Boiler\Commands;

use function array_keys;
use function array_values;
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

//        $this->generateClass('entity', config('boiler.entities_namespace'), $resource);
        $this->generateClass('service', config('boiler.services_namespace'), $resource.'Service', [
            '{{policies_namespace}}' => config('boiler.policies_namespace'),
            '{{entities_namespace}}' => config('boiler.entities_namespace'),
        ]);
        $this->generateClass('transformer', config('boiler.transformers_namespace'), $resource.'Transformer');
        $this->generateClass('policy', config('boiler.policies_namespace'), $resource.'Policy');
        $this->generateClass('controller', config('boiler.controllers_namespace'), $resource.'Controller');

        $this->info('please wait...');

        $this->composer->dumpAutoloads();
    }

    protected function generateClass($stubFile, $namespace, $entity, $type, $replace = [])
    {
        $path = $this->getPath($this->qualifyClass($namespace.'\\'.$entity));
        dd($path);
        $basePath = str_replace($this->laravel->basePath().'/', '', $path);

        if ($this->files->exists($path)) {
            $this->error($basePath.' already exists!');

            return;
        }

        $stub = $this->files->get(__DIR__.'/../stubs/'.$stubFile.'.stub');

        $stub = str_replace(array_merge(array_keys($replace), ['{{entity}}', '{{namespace}}']),array_merge(array_values($replace), [$resource, $namespace]),$stub);

        dd($stub);
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
        if (!$this->files->isDirectory(app_path(dirname($path)))) {
            $this->files->makeDirectory(app_path(dirname($path)), 0777, true, true);
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
}
