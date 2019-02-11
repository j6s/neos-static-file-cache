<?php

$autoloader = require __DIR__ . '/../Packages/Libraries/autoload.php';

$bootstrap = new \Neos\Flow\Core\Bootstrap('Testing', $autoloader);
$bootstrap->setPreselectedRequestHandlerClassName(\Neos\Flow\Tests\FunctionalTestRequestHandler::class);
$bootstrap->run();
