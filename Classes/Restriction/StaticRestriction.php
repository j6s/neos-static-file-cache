<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;

class StaticRestriction implements Restriction
{
    /** @var bool */
    private $state;

    public function __construct(bool $state)
    {
        $this->state = $state;
    }

    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        return $this->state;
    }
}
