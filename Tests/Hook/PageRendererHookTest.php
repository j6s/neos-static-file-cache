<?php
namespace J6s\StaticFileCache\Tests\Hook;

use J6s\StaticFileCache\Handler\CacheSaveHandler;
use J6s\StaticFileCache\Hook\PageRenderHook;
use J6s\StaticFileCache\Restriction\RestrictionCollection;
use J6s\StaticFileCache\Restriction\RestrictionFactory;
use Neos\Flow\Http\Request;
use Neos\Flow\Http\Response;
use Neos\Flow\Http\Uri;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\RequestInterface;
use Neos\Flow\Tests\Functional\Http\Fixtures\Controller\FooController;
use Neos\Flow\Tests\FunctionalTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Neos\Flow\Mvc\ResponseInterface;
use Neos\Neos\Controller\Frontend\NodeController;

class PageRendererHookTest extends FunctionalTestCase
{
    /** @var PageRenderHook */
    protected $subject;

    /** @var MockObject<CacheSaveHandler> */
    protected $cacheSaveHandler;

    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    /** @var ControllerInterface */
    protected $controller;

    /** @var Uri */
    protected $uri;

    public function setUp()
    {
        parent::setUp();

        $this->cacheSaveHandler = $this->getMockBuilder(CacheSaveHandler::class)
            ->setMethods([ 'save' ])
            ->getMock();

        // Restriction factory that builds empty restrictions.
        // Restrictions are tested on their own.
        $restrictionFactory = $this->getMockBuilder(RestrictionFactory::class)
            ->setMethods([ 'get' ])
            ->getMock();
        $restrictionFactory->method('get')->willReturn(new RestrictionCollection([]));

        $this->uri = new Uri('http://localhost/typo3/flow/test');
        $this->request = new ActionRequest(Request::create($this->uri));
        $this->response = new Response();
        $this->controller = new NodeController();

        $this->subject = $this->objectManager->get(PageRenderHook::class);
        $this->inject($this->subject, 'handler', $this->cacheSaveHandler);
        $this->inject($this->subject, 'restrictionFactory', $restrictionFactory);
    }

    public function testSavesResponseContentsInCache(): void
    {
        $this->cacheSaveHandler->expects($this->once())
            ->method('save')
            ->with($this->equalTo($this->uri));

        $this->subject->afterControllerInvocation(
            $this->request,
            $this->response,
            $this->controller
        );
    }
}
