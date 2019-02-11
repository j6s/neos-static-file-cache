<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;

class IgnoreUrlsWithQueryString implements Restriction
{
    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        return empty($request->getHttpRequest()->getUri()->getQuery());
    }
}
