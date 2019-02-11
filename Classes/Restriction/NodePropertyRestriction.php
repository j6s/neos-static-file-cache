<?php
namespace J6s\StaticFileCache\Restriction;

use J6s\StaticFileCache\Domain\Repository\NodeDataRepository;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodePropertyRestriction implements Restriction
{

    /**
     * @var NodeDataRepository
     * @Flow\Inject()
     */
    protected $nodeDataRepository;

    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        if (!$request->hasArgument('node')) {
            return false;
        }

        $nodeData = $this->nodeDataRepository->findOneByCombinedPath($request->getArgument('node'));
        if (!$nodeData) {
            return false;
        }

        return (bool) $nodeData->getProperty('cacheAsStaticFile');
    }
}
