<?php
namespace Qimnet\UpdateTrackerBundle\Templating;

use Symfony\Component\HttpKernel\Fragment\FragmentRendererInterface;
use Qimnet\UpdateTrackerBundle\CacheManager\CacheManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Renders a cached fragement.
 */
class CacheFragmentRenderer implements FragmentRendererInterface
{
    private $cacheManager;
    private $inlineRenderer;

    /**
     * Sets the fragment path that triggers the fragment listener.
     *
     * @param string $path The path
     *
     * @see FragmentListener
     */
    public function setFragmentPath($path)
    {
        $this->fragmentPath = $path;
    }
    public function __construct(FragmentRendererInterface $inlineRenderer, CacheManagerInterface $cacheManager)
    {
        $this->cacheManager = $cacheManager;
        $this->inlineRenderer = $inlineRenderer;
    }
    
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'cache';
    }
    
    /**
     * @inheritdoc
     * 
     * Additional optional options :
     * 
     *  * updateTrackerName : the update tracker namespaces the action is linked with.
     *  * ttl : the ttl of the object in the cache
     *  * repositoryName: the cache repository name
     */
    public function render($uri, Request $request, array $options = array())
    {
        $inlineRenderer = $this->inlineRenderer;
        
        if (!isset($options['updateTrackerName'])) {
            $options['updateTrackerName'] = 'global';
        }
        $uriHash = md5(serialize($uri));
        return $this->cacheManager->getObject($options['updateTrackerName'], 
                'controller/' . $uriHash,
                function() use($inlineRenderer, $uri, $request, $options) {
                    return $inlineRenderer->render($uri, $request, $options);
                },
                isset($options['ttl']) ? $options['ttl'] : null,
                isset($options['repositoryName']) ? $options['repositoryName'] : null);
    }
}

?>
