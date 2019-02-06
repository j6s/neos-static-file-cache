<?php
namespace J6s\StaticFileCache\Service;

interface FileSaver
{
    public function saveFile(string $path, string $content): void;
}
