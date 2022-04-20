<?php

declare(strict_types=1);

namespace Justasb\Phpunit\InjectionHooks\Test\Functional\App\Service;

class ProjectFiles implements \IteratorAggregate
{
    public function __construct(private string $projectDir)
    {
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \Iterator
    {
        foreach ($this->scanFiles($this->projectDir) as $filePath) {
            yield substr($filePath, strlen("$this->projectDir/"));
        }
    }

    private function scanFiles(string $dir): \Generator
    {
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = realpath("$dir/$file");

            if (is_dir($path)) {
                yield from $this->scanFiles($path);
            } else {
                yield $path;
            }
        }
    }
}
