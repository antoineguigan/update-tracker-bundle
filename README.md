Qimnet\UpdateTrackerBundle
==========================

This bundle is used to track updates on entites and cache results client side
or server side according to the update times.


Configuration
-------------


An `UpdateTracker` entity has to be added to the project to use the bundle. 

The following code can be used :

```php
<?php
namespace ACME\MyBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

use Qimnet\UpdateTrackerBundle\Entity\UpdateTracker as BaseUpdateTracker;

/**
 * @ORM\Entity
 */
class UpdateTracker extends BaseUpdateTracker
{

}
```


The name of the entity should then be added to your config.yml file :

```yaml
qimnet_update_tracker:
    entity_name: 'ACME\MyBundle\Entity\UpdateTracker'
```

Tracking entity changes
-----------------------

To track the changes of a given entity, you have two choices :

*   use the `Qimnet\UpdateTrackerBundle\Annotation\TrackUpdate` annotation on 
    your entity class
*   implement `Qimnet\UpdateTrackerBundle\UpdateTracker\TrackUpdateInterface` 
    in your entity class

The changes can then be tracked using the `qimnet.update_tracker.manager` 
service.


Using HTTP cache validation
---------------------------

To generate HTTP responses and use HTTP cache validation, use the 
`qimnet.update_tracker.http_cached_response_factory` service :

```php
<?php
namespace ACME\MyBundle\Controller;
class MyController
{
    public function myAction() 
    {
        $response = $this->get('qimnet.update_tracker.http_cached_response_factory')
            ->generate('my_namespace');
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        // Fetch content
        $response->setContent('Some content');
        return $response;
    }
}
```


Using server side caching
-------------------------

To use server side caching, enable the cache manager in your configuration 
file :

```yaml
qimnet_update_tracker:
    cache_manager:

        # True to enable the cache manager.
        enabled:              true
```

You can then request cached object using the `qimnet.update_tracker.cache_manager`
service :

```php
<?php
namespace ACME\MyBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

class MyController
{
    public function myAction($id)
    {
        $content = $this->get('qimnet.update_tracker.cache_manager')
            ->getObject('my_namespace', 'my_object/' . $id, function(){
                //Fetch the content and return it
                return 'Some content';
            })
        return new Response($content);
    }
}
```

You can also render cached fragments directly from yout templates by using the 
render tag with the ``cache`` strategy :

```twig
{{ render(controller("MyBundle:MyController:myAction"), { strategy: "cache", "updateTrackerName": "default", ttl: 120 }) }}
```



Cache entries are automatically deleted when the corresponding update tracker namespace
is changed.



The global namespace
--------------------

When the update time for a given namespace is read, it is always compared to the update time
of the global namespace, and the latest of the two is returned.