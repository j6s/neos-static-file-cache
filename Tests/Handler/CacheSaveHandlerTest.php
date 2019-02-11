<?php
namespace J6s\StaticFileCache\Tests\Handler;


use J6s\StaticFileCache\Handler\CacheSaveHandler;
use J6s\StaticFileCache\Service\FileSaver;
use J6s\StaticFileCache\Service\FilesystemSaver;
use J6s\StaticFileCache\Tests\Mocks\EnvironmentMock;
use J6s\StaticFileCache\Tests\Mocks\MockFileSaver;
use Neos\Flow\Http\Uri;
use Neos\Flow\Tests\FunctionalTestCase;
use Neos\Flow\Utility\Environment;
use PHPUnit\Framework\MockObject\MockObject;

class CacheSaveHandlerTest extends FunctionalTestCase
{

    /** @var CacheSaveHandler */
    private $subject;

    /** @var MockObject<Environment> */
    private $environment;

    /** @var MockObject<FileSaver> */
    private $fileSaver;

    /** @var string */
    private $pathWeb;

    public function setUp(): void
    {
        parent::setUp();

        $this->fileSaver = $this->getMockBuilder(FilesystemSaver::class)
            ->setMethods([ 'saveFile' ])
            ->getMock();

        $this->environment = $this->getMockBuilder(Environment::class)
            ->setMethods([ 'isRewriteEnabled' ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = $this->objectManager->get(CacheSaveHandler::class);
        $this->inject($this->subject, 'fileSaver', $this->fileSaver);
        $this->inject($this->subject, 'environment', $this->environment);
        $this->pathWeb = FLOW_PATH_WEB;
    }

    public function testShouldSaveContentsInFolder(): void
    {
        $this->environment->method('isRewriteEnabled')->willReturn(true);
        $this->fileSaver->expects($this->once())
            ->method('saveFile')
            ->with($this->equalTo($this->pathWeb . '/_StaticFileCache/https/example.com/my/fancy/page.html'));
        $this->subject->save(new Uri('https://example.com/my/fancy/page.html'), 'foo');
    }

    public function testShouldAddIndexPhpToPathIfRewriteNotEnabled(): void
    {
        $this->environment->method('isRewriteEnabled')->willReturn(false);
        $this->fileSaver->expects($this->once())
            ->method('saveFile')
            ->with($this->equalTo($this->pathWeb . '/_StaticFileCache/https/example.com/index.php/my/fancy/page.html'));
        $this->subject->save(new Uri('https://example.com/my/fancy/page.html'), 'foo');
    }

    public function testShouldAddIndexToPathIfNotAFile(): void
    {
        $this->environment->method('isRewriteEnabled')->willReturn(true);
        $this->fileSaver->expects($this->once())
            ->method('saveFile')
            ->with($this->equalTo($this->pathWeb . '/_StaticFileCache/https/example.com/my/fancy/index.html'));
        $this->subject->save(new Uri('https://example.com/my/fancy/'), 'foo');
    }
}
