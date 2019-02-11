<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use Neos\Flow\Http\Request;
use Neos\Flow\Http\Response;
use Neos\Flow\Http\Uri;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Tests\FunctionalTestCase;
use Neos\Neos\Controller\Frontend\NodeController;

abstract class AbstractRestrictionTest extends FunctionalTestCase
{
    /** @var ActionRequest */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var NodeController */
    protected $controller;

    protected function requestWithUri(string $uri): ActionRequest
    {
        return new ActionRequest(Request::create(new Uri($uri)));
    }

    public function setUp()
    {
        parent::setUp();
        $this->request = $this->requestWithUri('http://neos.test/foo');
        $this->response = new Response();
        $this->controller = new NodeController();
    }
}
