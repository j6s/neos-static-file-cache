<?php
namespace J6s\StaticFileCache\Tests\Mocks;


use Neos\Flow\Core\ApplicationContext;
use Neos\Flow\Utility\Environment;

class EnvironmentMock extends Environment
{
    private $rewriteEnabled = true;

    public function __construct() {
        parent::__construct(new ApplicationContext('Testing'));
    }

    public function setRewriteEnabled(bool $rewriteEnabled) {
        $this->rewriteEnabled = $rewriteEnabled;
    }

    public function isRewriteEnabled()
    {
        return $this->rewriteEnabled;
    }

}
