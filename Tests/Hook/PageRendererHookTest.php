<?php
namespace J6s\StaticFileCache\Tests\Hook;

use J6s\StaticFileCache\Domain\Repository\NodeDataRepository;
use J6s\StaticFileCache\Handler\CacheSaveHandler;
use J6s\StaticFileCache\Hook\PageRenderHook;
use Neos\ContentRepository\Domain\Model\NodeData;
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

    /** @var Request */
    protected $httpRequest;

    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    /** @var ControllerInterface */
    protected $controller;

    /** @var Uri */
    protected $uri;

    /** @var MockObject<NodeData> */
    protected $nodeData;

    public function setUp()
    {
        parent::setUp();

        $this->cacheSaveHandler = $this->getMockBuilder(CacheSaveHandler::class)
            ->setMethods([ 'save' ])
            ->getMock();

        $this->nodeData = $this->getMockBuilder(NodeData::class)
            ->setMethods([ 'getProperty' ])
            ->disableOriginalConstructor()
            ->getMock();

        $nodeDataRepository = $this->getMockBuilder(NodeDataRepository::class)
            ->setMethods([ 'findOneByCombinedPath' ])
            ->getMock();
        $nodeDataRepository->method('findOneByCombinedPath')->willReturn($this->nodeData);

        $this->uri = new Uri('http://localhost/typo3/flow/test');
        $this->httpRequest = Request::create($this->uri);
        $this->request = new ActionRequest($this->httpRequest);
        $this->request->setArgument('node', '/sites/foo-bar');
        $this->response = new Response();
        $this->controller = new NodeController();

        $this->subject = $this->objectManager->get(PageRenderHook::class);
        $this->inject($this->subject, 'handler', $this->cacheSaveHandler);
        $this->inject($this->subject, 'nodeDataRepository', $nodeDataRepository);
    }

    public function testSavesResponseContentsInCache(): void
    {
        $this->nodeData->method('getProperty')
            ->with('cacheAsStaticFile')
            ->willReturn(true);

        $this->cacheSaveHandler->expects($this->once())
            ->method('save')
            ->with($this->equalTo($this->uri));

        $this->subject->afterControllerInvocation(
            $this->request,
            $this->response,
            $this->controller
        );
    }

    public function testDoesNotStoreSubRequestsInCache(): void
    {
        $this->cacheSaveHandler->expects($this->never())->method('save');
        $this->subject->afterControllerInvocation(
            new ActionRequest($this->request),
            $this->response,
            $this->controller
        );
    }

    public function testOnlyStoresNodeRequestsInCache(): void
    {
        $this->cacheSaveHandler->expects($this->never())->method('save');
        $this->subject->afterControllerInvocation(
            $this->request,
            $this->response,
            new FooController()
        );
    }

    public function testRespectsCacheControlHeader(): void
    {
        $this->httpRequest->getHeaders()->set('Cache-Control', 'no-cache');

        $this->cacheSaveHandler->expects($this->never())->method('save');
        $this->subject->afterControllerInvocation(
            $this->request,
            $this->response,
            $this->controller
        );
    }

    public function testRespectsPragmaHeader(): void
    {
        $this->httpRequest->getHeaders()->set('Pragma', 'no-cache');

        $this->cacheSaveHandler->expects($this->never())->method('save');
        $this->subject->afterControllerInvocation(
            $this->request,
            $this->response,
            $this->controller
        );
    }

}
