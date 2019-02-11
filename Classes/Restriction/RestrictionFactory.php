<?php
namespace J6s\StaticFileCache\Restriction;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManager;

/**
 * @Flow\Scope("singleton")
 */
class RestrictionFactory
{

    /**
     * @var ObjectManager
     * @Flow\Inject()
     */
    protected $objectManager;

    public function get(): Restriction
    {
        return new RestrictionCollection([
            $this->objectManager->get(OnlyAllowMainRequest::class),
            $this->objectManager->get(OnlyAllowNodeController::class),
            $this->objectManager->get(RespectCacheControlHeaders::class),
            $this->objectManager->get(NodePropertyRestriction::class),
            $this->objectManager->get(IgnoreUrlsWithQueryString::class),
            $this->objectManager->get(IgnoreNeosBackendUrls::class),
        ]);
    }
}
