<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;

class IgnoreNeosBackendUrls implements Restriction
{
    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        return !preg_match('/^\/?neos\//', $request->getHttpRequest()->getUri()->getPath());
    }
}
