<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Mvc\ResponseInterface;

class RestrictionCollection implements Restriction
{

    /**
     * @var Restriction[]
     */
    protected $restrictions = [];

    public function __construct(array $restrictions)
    {
        $this->restrictions = $restrictions;
    }

    public function allow(ActionRequest $request, ResponseInterface $response, ControllerInterface $controller): bool
    {
        foreach ($this->restrictions as $restriction) {
            if (!$restriction->allow($request, $response, $controller)) {
                return false;
            }
        }
        return true;
    }

    public function contains(string $className): bool
    {
        foreach ($this->restrictions as $restriction) {
            if (get_class($restriction) === $className) {
                return true;
            }
        }
        return false;
    }
}
