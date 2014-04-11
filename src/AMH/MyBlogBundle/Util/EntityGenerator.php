<?php
namespace AMH\MyBlogBundle\Util;

use Doctrine\ORM\EntityManagerInterface;
use AMH\MyBlogBundle\Entity\User\Role;
use AMH\MyBlogBundle\Entity\Blog\Post;

class EntityGenerator{
	/**
	@var EntityManagerInterface
	*/
	private $entityManager;
	
	public function __construct(EntityManagerInterface $em){
		$this->setEntityManager($em);
	}
	/**
	@return EntityManagerInterface
	*/
	public function getEntityManager(){
		return $this->entityManager;
	}
	
	public function setEntityManager(EntityManagerInterface $em){
		$this->entityManager=$em;
	}
	/**
	@param array of strings that will be used as Role objs role props.
	
	@return array of Role objs persisted
	*/
	public function createRoles(array $roles){
		$es=array();
		foreach($roles as $r){
			$role=new Role($r);
			$this->getEntityManager()->persist($role);
			$es[]=$role;
		}
		$this->getEntityManager()->flush();
		return $es;
	}
	/**
	@param int Posts count.
	
	@return array of Post.
	*/
	public function createPosts($c){
		
	}
}
?>
