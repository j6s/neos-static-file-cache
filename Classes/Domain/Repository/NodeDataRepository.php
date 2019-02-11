<?php
namespace J6s\StaticFileCache\Domain\Repository;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodeDataRepository extends \Neos\ContentRepository\Domain\Repository\NodeDataRepository
{

    /**
     * @var WorkspaceRepository
     * @Flow\Inject()
     */
    protected $workspaceRepository;

    public function findOneByCombinedPath(string $path): ?NodeData
    {
        $pathParts = explode('@', $path);
        $path = $pathParts[0];
        $workspace = \count($pathParts) > 1 ? $pathParts[1] : 'live';

        return $this->findOneByPath(
            $path,
            $this->workspaceRepository->findByIdentifier($workspace)
        );
    }
}
