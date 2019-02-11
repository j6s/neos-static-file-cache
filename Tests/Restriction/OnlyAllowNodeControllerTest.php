<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\OnlyAllowNodeController;
use Neos\Flow\Tests\Functional\Http\Fixtures\Controller\FooController;
use Neos\Neos\Controller\Frontend\NodeController;

class OnlyAllowNodeControllerTest extends AbstractRestrictionTest
{

    public function testAllowsNodeController(): void
    {
        $restriction = new OnlyAllowNodeController();
        $this->assertTrue($restriction->allow(
            $this->request,
            $this->response,
            new NodeController()
        ));
    }

    public function testDoesNotAllowOtherControllers(): void
    {
        $restriction = new OnlyAllowNodeController();
        $this->assertFalse($restriction->allow(
            $this->request,
            $this->response,
            new FooController()
        ));
    }
}
