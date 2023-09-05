<?php

declare(strict_types=1);


use Exception;
use Str;

trait StoresAssetForTests
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function storeResource(string $name, $asset, bool $force = false): void
    {
        $theFile = $this->getResourceName($name, debug_backtrace()[0]['file']);
        if (file_exists($theFile) && ! $force) {
            throw new Exception("{$theFile} already exists.");
        }
        file_put_contents($theFile, serialize($asset));
    }

    public function getResourceName($name, $backtrace): string
    {
        $directory = str($backtrace)->replace('tests', 'tests/resources')->beforeLast('/');
        if ( ! is_dir((string) $directory)) {
            mkdir((string) $directory, 0777, true);
        }

        return $directory->append('/', Str::slug($name, '_'), '.txt')->toString();
    }

    public function getResource($name)
    {
        $directory = str(debug_backtrace()[0]['file'])->replace('tests', 'tests/resources')->beforeLast('/');
        $theFile = $directory->append('/', Str::slug($name, '_'), '.txt')->toString();

        if ( ! file_exists($theFile)) {
            throw new Exception("{$theFile} doesn't exists.");
        }

        return unserialize(file_get_contents($theFile));
    }
}
