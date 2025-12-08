<?php

namespace App\Support;

use Illuminate\View\Compilers\BladeCompiler;

class WindowsBladeCompiler extends BladeCompiler
{
    /**
     * Compile the view at the given path with retry logic for Windows file locks.
     *
     * @param  string|null  $path
     * @return void
     */
    public function compile($path = null)
    {
        if ($path) {
            $this->setPath($path);
        }

        if (!is_null($this->cachePath)) {
            $contents = $this->compileString($this->files->get($this->getPath()));

            // Windows file lock retry logic
            $maxRetries = 3;
            $retryDelay = 100000; // 0.1 seconds in microseconds
            $lastException = null;

            for ($i = 0; $i < $maxRetries; $i++) {
                try {
                    // Ensure directory exists
                    if (!is_dir(dirname($this->getCompiledPath($this->getPath())))) {
                        if (!@mkdir(dirname($this->getCompiledPath($this->getPath())), 0755, true) && !is_dir(dirname($this->getCompiledPath($this->getPath())))) {
                            throw new \RuntimeException(sprintf('Directory "%s" was not created', dirname($this->getCompiledPath($this->getPath()))));
                        }
                    }

                    // Try to write the file
                    $this->files->put($this->getCompiledPath($this->
