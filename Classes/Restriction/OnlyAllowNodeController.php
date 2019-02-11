<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;
use Neos\Neos\Controller\Frontend\NodeController;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class OnlyAllowNodeController implements Restriction
{

    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        return $controller instanceof NodeController;
    }
}
