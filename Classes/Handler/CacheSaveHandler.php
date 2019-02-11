<?php
namespace J6s\StaticFileCache\Handler;

use J6s\StaticFileCache\Service\FileSaver;
use J6s\StaticFileCache\Service\FilesystemSaver;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Utility\Environment;
use Neos\Utility\Files;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Scope("singleton")
 */
class CacheSaveHandler
{

    /**
     * @var Environment
     * @Flow\Inject()
     */
    protected $environment;

    /**
     * @var FileSaver
     * @Flow\Inject()
     */
    protected $fileSaver;

    public function save(UriInterface $url, string $body): void
    {
        $this->fileSaver->saveFile($this->pathFromUrl($url), $body);
    }

    private function pathFromUrl(UriInterface $url): string
    {
        $path = [
            FLOW_PATH_WEB,
            '_StaticFileCache',
            $url->getScheme(),
            $url->getHost(),
        ];

        if (!$this->environment->isRewriteEnabled()) {
            $path[] = 'index.php';
        }

        $leaf = null;
        foreach (explode('/', $url->getPath()) as $item) {
            if ($item) {
                $path[] = $item;
                $leaf = $item;
            }
        }

        if (!$leaf || strpos($leaf, '.html') === false) {
            $path[] = 'index.html';
        }

        return implode(DIRECTORY_SEPARATOR, $path);
    }
}
