<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\OnlyAllowMainRequest;
use Neos\Flow\Mvc\ActionRequest;

class OnlyAllowMainRequestTest extends AbstractRestrictionTest
{

    public function testAllowsMainRequest()
    {
        $restriction = new OnlyAllowMainRequest();
        $this->assertTrue($restriction->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowSubRequest()
    {
        $restriction = new OnlyAllowMainRequest();
        $this->assertFalse($restriction->allow(
            new ActionRequest($this->request),
            $this->response,
            $this->controller
        ));
    }

}
