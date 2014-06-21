<?php
namespace AMH\MyBlogBundle\Util;

use Memcache;
use Doctrine\Common\Cache\Cache as DoctrineCache;

class CacheFactory{
	/**
	@return Memcache
	*/
	public function createMemcached(){
		$c=new Memcache();
		$success=$c->connect('localhost','11211');
		if(!$success) $c=NULL;
		return $c;
	}
	/**
	@return DoctrineCache
	*/
	public function createDoctrineCache($simpleCache){
		$cache=NULL;
		if($simpleCache instanceof Memcache){
			$cache=new \Doctrine\Common\Cache\MemcacheCache();
			$cache->setMemcache($simpleCache);
		}
		else{
			throw new \InvalidArgumentException('Unknown cache handler given');
		}
		return $cache;
	}
}
