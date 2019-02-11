<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\NodePropertyRestriction;
use J6s\StaticFileCache\Restriction\OnlyAllowMainRequest;
use J6s\StaticFileCache\Restriction\OnlyAllowNodeController;
use J6s\StaticFileCache\Restriction\RespectCacheControlHeaders;
use J6s\StaticFileCache\Restriction\RestrictionCollection;
use J6s\StaticFileCache\Restriction\RestrictionFactory;

class RestrictionFactoryTest extends AbstractRestrictionTest
{
    /** @var RestrictionFactory */
    protected $subject;

    public function setUp()
    {
        parent::setUp();
        $this->subject = new RestrictionFactory();
        $this->inject($this->subject, 'objectManager', $this->objectManager);
    }

    public function testReturnsCollection(): void
    {
        $this->assertInstanceOf(RestrictionCollection::class, $this->subject->get());
    }

    public function testContainsDefaultRestrictions(): void
    {
        /** @var RestrictionCollection $restrictions */
        $restrictions = $this->subject->get();
        $this->assertTrue($restrictions->contains(OnlyAllowMainRequest::class));
        $this->assertTrue($restrictions->contains(OnlyAllowNodeController::class));
        $this->assertTrue($restrictions->contains(RespectCacheControlHeaders::class));
        $this->assertTrue($restrictions->contains(NodePropertyRestriction::class));
    }
}
