<?php
namespace J6s\StaticFileCache;

use J6s\StaticFileCache\Hook\PageRenderHook;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Mvc\Dispatcher;
use Neos\Flow\Package\Package as BasePackage;

class Package extends BasePackage
{

    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(
            Dispatcher::class,
            'afterControllerInvocation',
            PageRenderHook::class,
            'afterControllerInvocation'
        );
    }
}
