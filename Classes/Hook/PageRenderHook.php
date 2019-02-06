<?php
namespace J6s\StaticFileCache\Hook;

use J6s\StaticFileCache\Handler\CacheSaveHandler;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Http\Request;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\RequestInterface;
use Neos\Flow\Mvc\ResponseInterface;
use Neos\Neos\Controller\Frontend\NodeController;

class PageRenderHook
{
    /**
     * @var CacheSaveHandler
     * @Flow\Inject()
     */
    protected $handler;

    public function afterControllerInvocation(
        RequestInterface $request,
        ResponseInterface $response,
        ControllerInterface $controller
    ): void {
        if (!($request instanceof ActionRequest) || !$this->shouldBeSaved($request)) {
            return;
        }

        $this->handler->save(
            $request->getHttpRequest()->getUri(),
            $response->getContent()
        );
    }

    private function shouldBeSaved(ActionRequest $request): bool
    {
        if (!$request->isMainRequest()) {
            return false;
        }

        if ($request->getControllerObjectName() !== NodeController::class) {
            return false;
        }

        $http = $request->getHttpRequest();
        if ($http->getHeader('cache-control') === 'no-cache' || $http->getHeader('pragma') === 'no-cache') {
            return false;
        }

        return true;
    }
}
