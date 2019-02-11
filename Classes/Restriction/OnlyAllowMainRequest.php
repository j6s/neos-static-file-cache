<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class OnlyAllowMainRequest implements Restriction
{
    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        return $request->isMainRequest();
    }
}
