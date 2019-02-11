<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\IgnoreNeosBackendUrls;

class IgnoreNeosBackendUrlsTest extends AbstractRestrictionTest
{

    public function testDoesNotAllowBackendUrls(): void
    {
        $restriction = new IgnoreNeosBackendUrls();
        $this->assertFalse($restriction->allow(
            $this->requestWithUri('https://neos.test/neos/content'),
            $this->response,
            $this->controller
        ));
    }

    public function testAllowsFrontendUrls(): void
    {
        $restriction = new IgnoreNeosBackendUrls();
        $this->assertTrue($restriction->allow(
            $this->requestWithUri('https://neos.test/foo'),
            $this->response,
            $this->controller
        ));
    }

    public function testAllowsFrontendUrlsThatLookSimilarToBackendUrls(): void
    {
        $restriction = new IgnoreNeosBackendUrls();
        $this->assertTrue($restriction->allow(
            $this->requestWithUri('https://neos.test/neos-is-cool'),
            $this->response,
            $this->controller
        ));
    }
}
