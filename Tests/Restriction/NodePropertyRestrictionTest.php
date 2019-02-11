<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Domain\Repository\NodeDataRepository;
use J6s\StaticFileCache\Restriction\NodePropertyRestriction;
use Neos\ContentRepository\Domain\Model\NodeData;
use PHPUnit\Framework\MockObject\MockObject;

class NodePropertyRestrictionTest extends AbstractRestrictionTest
{

    /** @var MockObject<NodeData> */
    protected $nodeData;

    /** @var NodePropertyRestriction */
    protected $subject;

    public function setUp()
    {
        parent::setUp();

        $this->nodeData = $this->getMockBuilder(NodeData::class)
            ->setMethods([ 'getProperty' ])
            ->disableOriginalConstructor()
            ->getMock();

        $repository = $this->getMockBuilder(NodeDataRepository::class)
            ->setMethods([ 'findOneByCombinedPath' ])
            ->getMock();

        $repository->method('findOneByCombinedPath')
            ->willReturnMap([
                ['/sites/foo-bar@live', $this->nodeData]
            ]);

        $this->subject = new NodePropertyRestriction();
        $this->inject($this->subject, 'nodeDataRepository', $repository);
    }

    public function testDoesNotAllowIfNoNodeInRequest(): void
    {
        $this->assertFalse($this->subject->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowIfNodeNotFound(): void
    {
        $this->request->setArgument('node', '/sites/unknown@live');
        $this->assertFalse($this->subject->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowIfPropertyNotFound(): void
    {
        $this->request->setArgument('node', '/sites/foo-bar@live');
        $this->assertFalse($this->subject->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testDoesNotAllowIfCheckboxNotSet(): void
    {
        $this->request->setArgument('node', '/sites/foo-bar@live');
        $this->nodeData->expects($this->once())
            ->method('getProperty')
            ->with($this->equalTo('cacheAsStaticFile'))
            ->willReturn(false);

        $this->assertFalse($this->subject->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testAllowsIfCheckboxSet(): void
    {
        $this->request->setArgument('node', '/sites/foo-bar@live');
        $this->nodeData->expects($this->once())
            ->method('getProperty')
            ->with($this->equalTo('cacheAsStaticFile'))
            ->willReturn(true);

        $this->assertTrue($this->subject->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

}
