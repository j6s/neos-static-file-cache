<?php
namespace J6s\StaticFileCache;


use J6s\PhpArch\Component\Architecture;
use J6s\PhpArch\PhpArch;
use J6s\PhpArch\Validation\MustBeSelfContained;
use Neos\Flow\Tests\UnitTestCase;

class ArchitectureTest extends UnitTestCase
{

    public function testArchitecture()
    {
        $architecture = (new Architecture())
            ->components([
                'Hooks' => 'J6s\\StaticFileCache\\Hook',
                'Repository' => 'J6s\\StaticFileCache\\Domain\\Repository',
                'Handler' => 'J6s\\StaticFileCache\\Handler',
                'Restriction' => 'J6s\\StaticFileCache\\Restriction',
                'FileSaver' => 'J6s\\StaticFileCache\\Service',
                'Neos' => 'Neos\\'
            ]);

        $architecture->component('Repository')->mustOnlyDependOn('Neos');

        $architecture->component('Restriction')
            ->mustNotDependOn('Hooks')
            ->andMustNotDependOn('Handler')
            ->andMustNotDependOn('FileSaver');

        $architecture->component('FileSaver')
            ->mustNotDependOn('Hooks')
            ->andMustNotDependOn('Handler');
        
        $architecture->component('Hooks')
            ->mustNotDirectlyDependOn('FileSaver');

        return (new PhpArch())
            ->fromDirectory(__DIR__ . '/../Classes')
            ->validate($architecture)
            ->assertHasNoErrors();
    }

}
