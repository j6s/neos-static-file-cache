<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\StaticRestriction;

class StaticRestrictionTest extends AbstractRestrictionTest
{

    public function testReturnsConstructorArgument()
    {
        $this->assertTrue((new StaticRestriction(true))->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
        $this->assertFalse((new StaticRestriction(false))->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

}
