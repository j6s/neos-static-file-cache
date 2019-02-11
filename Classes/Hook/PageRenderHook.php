<?php
namespace J6s\StaticFileCache\Hook;

use J6s\StaticFileCache\Domain\Repository\NodeDataRepository;
use J6s\StaticFileCache\Handler\CacheSaveHandler;
use J6s\StaticFileCache\Restriction\RestrictionFactory;
use Neos\Flow\Annotations as Flow;
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

    /**
     * @var RestrictionFactory
     * @Flow\Inject()
     */
    protected $restrictionFactory;

    public function afterControllerInvocation(
        RequestInterface $request,
        ResponseInterface $response,
        ControllerInterface $controller
    ): void {
        if (!($request instanceof ActionRequest) ||
            !$this->restrictionFactory->get()->allow($request, $response, $controller)
        ) {
            return;
        }

        $this->handler->save(
            $request->getHttpRequest()->getUri(),
            $response->getContent()
        );
    }
}
