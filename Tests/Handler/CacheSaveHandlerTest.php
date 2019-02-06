<?php
namespace J6s\StaticFileCache\Tests\Handler;


use J6s\StaticFileCache\Handler\CacheSaveHandler;
use J6s\StaticFileCache\Tests\Mocks\EnvironmentMock;
use J6s\StaticFileCache\Tests\Mocks\MockFileSaver;
use Neos\Flow\Http\Uri;
use PHPUnit\Framework\TestCase;

class CacheSaveHandlerTest extends TestCase
{

    /** @var CacheSaveHandler */
    private $subject;

    /** @var EnvironmentMock */
    private $environment;

    /** @var MockFileSaver */
    private $fileSaver;

    /** @var string */
    private $pathWeb;

    public function setUp(): void
    {
        $this->subject = new CacheSaveHandler();
        $this->environment = new EnvironmentMock();
        $this->fileSaver = new MockFileSaver();
        $this->subject->injectEnvironment($this->environment);
        $this->subject->injectFileSaver($this->fileSaver);

        if (!defined('FLOW_PATH_WEB')) {
            define('FLOW_PATH_WEB', sys_get_temp_dir());
        }
        $this->pathWeb = FLOW_PATH_WEB;
    }

    public function testShouldSaveContentsInFolder()
    {
        $this->subject->save(new Uri('https://example.com/my/fancy/page.html'), 'foo');
        $call = $this->fileSaver->calls()[0];
        $this->assertEquals(
            $this->pathWeb . '/_StaticFileCache/https/example.com/my/fancy/page.html',
            $call['path']
        );
    }

    public function testShouldAddIndexPhpToPathIfRewriteNotEnabled()
    {
        $this->environment->setRewriteEnabled(false);
        $this->subject->save(new Uri('https://example.com/my/fancy/page.html'), 'foo');
        $call = $this->fileSaver->calls()[0];
        $this->assertEquals(
            $this->pathWeb . '/_StaticFileCache/https/example.com/index.php/my/fancy/page.html',
            $call['path']
        );
    }

    public function testShouldIgnorePagesWithQueryString()
    {
        $this->subject->save(new Uri('https://example.com/my/fancy/page.html?foo=bar'), 'foo');
        $this->assertFalse($this->fileSaver->wasCalled());
    }

    public function testShouldAddIndexToPathIfNotAFile()
    {
        $this->subject->save(new Uri('https://example.com/my/fancy/'), 'foo');
        $call = $this->fileSaver->calls()[0];
        $this->assertEquals(
            $this->pathWeb . '/_StaticFileCache/https/example.com/my/fancy/index.html',
            $call['path']
        );
    }

}
