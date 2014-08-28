<?php
namespace AMH\MyBlogBundle\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use AMH\MyBlogBundle\Util\GeoDetector;
use Symfony\Component\Routing\Router;

/**
 * Class GeoSubdomainEventListener
 * @package AMH\MyBlogBundle\Event
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class GeoSubdomainEventListener {
    /**
     * @var GeoDetector
     */
    private $geoDetector;

    /**
     * @var Router
     */
    private $router;

    public function __construct(GeoDetector $detector, Router $router){
        $this->geoDetector=$detector;
        $this->router=$router;
    }

    /**
     * @param GetResponseEvent $event
     * @return void
     */
    public function onRequest(GetResponseEvent $event){
        if(!$event->isMasterRequest()){
            return;
        }
        /** @var string $locale */
        $geoParam=$event->getRequest()->get('_geo');
        $geo=$this->geoDetector->byIp($event->getRequest()->getClientIp());
        if($geo && $geo!=$geoParam){
            $routeParams=$this->router->match($event->getRequest()->getPathInfo());
            $routeParams['_geo']=$geo;
            $routeName=$routeParams['_route'];
            unset($routeParams['_route']);
            $event->setResponse(new RedirectResponse($this->router->generate($routeName, $routeParams)));
        }
    }
} 