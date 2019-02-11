<?php
namespace J6s\StaticFileCache\Tests\Restriction;


use J6s\StaticFileCache\Restriction\OnlyAllowNodeController;
use J6s\StaticFileCache\Restriction\RestrictionCollection;
use J6s\StaticFileCache\Restriction\StaticRestriction;

class RestrictionCollectionTest extends AbstractRestrictionTest
{

    public function testReturnsTrueIfEmpty(): void
    {
        $collection = new RestrictionCollection([]);
        $this->assertTrue($collection->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testReturnsTrueIfAllChildrenReturnTrue(): void
    {
        $collection = new RestrictionCollection([
            new StaticRestriction(true),
            new StaticRestriction(true),
            new StaticRestriction(true),
        ]);
        $this->assertTrue($collection->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testReturnsFalseIfOneChildrenReturnsFalse(): void
    {
        $collection = new RestrictionCollection([
            new StaticRestriction(true),
            new StaticRestriction(false),
            new StaticRestriction(true),
        ]);
        $this->assertFalse($collection->allow(
            $this->request,
            $this->response,
            $this->controller
        ));
    }

    public function testAllowsCheckingForChildRestrictions(): void
    {
        $collection = new RestrictionCollection([
            new StaticRestriction(true),
        ]);

        $this->assertTrue($collection->contains(StaticRestriction::class));
        $this->assertFalse($collection->contains(OnlyAllowNodeController::class));
    }

}
