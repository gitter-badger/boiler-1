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
        $this->generateClass('service', config('boiler.services_namespace'), $resource.'Service');
        $this->generateClass('transformer', config('boiler.transformers_namespace'), $resource.'Transformer');
        $this->generateClass('policy', config('boiler.policies_namespace'), $resource.'Policy');
        $this->generateClass('controller', config('boiler.controllers_namespace'), $resource.'Controller');

        $this->info('please wait...');

        $this->composer->dumpAutoloads();
    }

    /**
     * @param string $stubFile
     * @param string $resource
     */
    protected function generateClass($stubFile, $namespace, $resource)
    {
        $path = $this->getPath($this->qualifyClass($namespace.'\\'.$resource));
        $basePath = str_replace($this->laravel->basePath().'/', '', $path);

        if ($this->files->exists($path)) {
            $this->error($basePath.' already exists!');

            return;
        }

        $stub = $this->files->get(__DIR__.'/../stubs/'.$stubFile.'.stub');

        $stub = str_replace(
            ['{{entity}}', '{{namespace}}'],
            [$resource, $namespace],
            $stub
        );

        $this->files->put($path, $stub);

        $this->info($basePath . ' Created successfully');
    }

    /**
     * @param string $name
     */
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

    /**
     * @param string $rootNamespace
     */
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
        if (! $this->files->isDirectory(app_path(dirname($path)))) {
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
