<?php
namespace J6s\StaticFileCache\Tests\Mocks;


use J6s\StaticFileCache\Service\FileSaver;

class MockFileSaver implements FileSaver
{
    private $calls = [];

    public function calls(): array
    {
        return $this->calls;
    }

    public function wasCalled(): bool
    {
        return !empty($this->calls);
    }

    public function saveFile(string $path, string $content): void
    {
        $this->calls[] = compact('path', 'content');
    }
}
