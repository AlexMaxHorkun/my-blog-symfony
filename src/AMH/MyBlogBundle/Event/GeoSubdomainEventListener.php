<?php
namespace AMH\MyBlogBundle\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
/**
 * Class GeoSubdomainEventListener
 * @package AMH\MyBlogBundle\Event
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class GeoSubdomainEventListener {
    /**
     * @param GetResponseEvent $event
     * @return void
     */
    public function onRequest(GetResponseEvent $event){
        if(!$event->isMasterRequest()){
            return;
        }
        /** @var string $locale */
        $locale=$event->getRequest()->getLocale();
        $host=$event->getRequest()->getHost();
        print_r($locale." ".$host); die();
    }
} 