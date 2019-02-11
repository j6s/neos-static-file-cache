<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\IgnoreUrlsWithQueryString;
use Neos\Flow\Http\Uri;

class IgnoreUrlsWithQueryStringTest extends AbstractRestrictionTest
{

    public function testAllowsIfNoQueryString(): void
    {
        $restriction = new IgnoreUrlsWithQueryString();
        $this->assertTrue($restriction->allow(
            $this->requestWithUri('https://neos.test/foo'),
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowIfQueryStringOnUrl(): void
    {
        $restriction = new IgnoreUrlsWithQueryString();
        $this->assertFalse($restriction->allow(
            $this->requestWithUri('https://neos.test/foo?foo=bar'),
            $this->response,
            $this->controller
        ));
    }

}
