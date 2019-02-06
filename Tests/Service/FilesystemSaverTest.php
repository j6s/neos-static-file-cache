<?php
namespace J6s\StaticFileCache\Tests\Service;

use J6s\StaticFileCache\Service\FilesystemSaver;
use PHPUnit\Framework\TestCase;

class FilesystemSaverTest extends TestCase
{
    /** @var FilesystemSaver */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new FilesystemSaver();
    }

    /** @dataProvider getTemporaryFileName */
    public function testCreatesFileIfNotExists(string $name): void
    {
        $this->assertFileNotExists($name);
        $this->subject->saveFile($name, 'foo');
        $this->assertFileExists($name);
    }

    /** @dataProvider getTemporaryFileName */
    public function testOverwritesPreviousFileContents(string $name): void
    {
        file_put_contents($name, 'foo');
        $this->assertStringEqualsFile($name, 'foo');
        $this->subject->saveFile($name, 'bar');
        $this->assertStringEqualsFile($name, 'bar');
    }

    /** @dataProvider getTemporaryFileName */
    public function testGeneratesSubdirectories(string $directory): void
    {
        $directory .= 'deep' . DIRECTORY_SEPARATOR . 'path';
        $name = $directory . DIRECTORY_SEPARATOR . 'my-file.txt';
        $this->assertDirectoryNotExists($directory);
        $this->subject->saveFile($name, 'foo');
        $this->assertDirectoryExists($directory);
    }

    /**
     * Returns a number of random file names in the systems temporary directory.
     * Note: Not using `tempnam` as that would also create the file -
     *       we only need the filename here.
     *
     * @return string[][]
     */
    public function getTemporaryFileName(): array
    {
        return [
            [ sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(mt_rand()) ],
            [ sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(mt_rand()) ],
            [ sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(mt_rand()) ],
            [ sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(mt_rand()) ],
            [ sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(mt_rand()) ],
            [ sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(mt_rand()) ],
        ];
    }

}
