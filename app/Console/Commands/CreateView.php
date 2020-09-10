<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class CreateView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {view}';

    protected $viewPath;

    protected $content;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a view in the resources/views directory with a given name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->viewPath = Config::get('view.paths')[0];
        $this->content = "@extends('app')\n@section('title', 'This is my title')\n@section('content')\n@endsection";

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $view = str_replace('/', '\\', $this->argument('view') . ".blade.php");
        $path = $this->viewPath . "\\" . $view;

        $explodedArgument = explode('\\', $view);

        if (count($explodedArgument) == 1) {
            $this->createBlade($path);
        } else {
            $lastDir = '';
            foreach ($explodedArgument as $argument) {
                $this->createDirectory($explodedArgument, $argument, $lastDir);

                $lastDir .= $argument . "\\";
            }

            $this->createBlade($path);
        }
    }

    /**
     * @param $text
     * @param $color
     */
    private function writeToConsole($text, $color)
    {
        switch ($color) {
            case 'red':
                echo "\033[31m" . $text;
                break;
            case 'green':
                echo "\033[32m" . $text;
                break;
            case 'yellow':
                echo "\033[33m" . $text;
                break;
            case 'blue':
                echo "\033[34m" . $text;
                break;
            default:
                echo "\033[37m" . $text;
        }
    }

    /**
     * @param string $path
     */
    private function createBlade(string $path): void
    {
        if (!file_exists($path)) {
            file_put_contents($path, $this->content);
            $this->writeToConsole('Laravel blade ' . $this->argument('view') . '.blade.php' . ' created successfully', 'green');
        } else {
            $this->writeToConsole('This file already exists', 'red');
        }
    }

    /**
     * @param array $explodedArgument
     * @param string $argument
     * @param string $lastDir
     */
    private function createDirectory(array $explodedArgument, string $argument, string $lastDir): void
    {
        if (last($explodedArgument) !== $argument) {
            if (!is_dir($this->viewPath . "\\" . $lastDir . $argument)) {
                mkdir($this->viewPath . "\\" . $lastDir . $argument);
            }
        }
    }
}
