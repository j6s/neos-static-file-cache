<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class RespectCacheControlHeaders implements Restriction
{

    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        $http = $request->getHttpRequest();
        return $http->getHeader('Cache-Control') !== 'no-cache' && $http->getHeader('Pragma') !== 'no-cache';
    }
}
