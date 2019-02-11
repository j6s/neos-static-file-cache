<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\RespectCacheControlHeaders;

class RespectCacheControlHeadersTest extends AbstractRestrictionTest
{

    public function testAllowsDefaultRequests(): void
    {
        $restriction = new RespectCacheControlHeaders();
        $this->assertTrue($restriction->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowRequestsWithCacheControlHeader(): void
    {
        $this->request->getHttpRequest()->getHeaders()->set('Cache-Control', 'no-cache');
        $restriction = new RespectCacheControlHeaders();
        $this->assertFalse($restriction->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowRequestsWithPragmaHeader(): void
    {
        $this->request->getHttpRequest()->getHeaders()->set('Pragma', 'no-cache');
        $restriction = new RespectCacheControlHeaders();
        $this->assertFalse($restriction->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

}
