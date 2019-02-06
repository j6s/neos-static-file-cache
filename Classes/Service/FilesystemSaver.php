<?php
namespace J6s\StaticFileCache\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;

/**
 * @Flow\Scope("singleton")
 */
class FilesystemSaver implements FileSaver
{

    public function saveFile(string $path, string $content): void
    {
        $this->ensureDirectoryExists($path);
        file_put_contents($path, $content);
    }

    private function ensureDirectoryExists(string $path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $directory = '/' . Files::concatenatePaths(\array_slice($parts, 0, -1));
        Files::createDirectoryRecursively($directory);
    }
}
