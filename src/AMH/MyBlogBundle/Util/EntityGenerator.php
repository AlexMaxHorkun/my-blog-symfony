<?php
namespace AMH\MyBlogBundle\Util;

use Doctrine\ORM\EntityManagerInterface;
use AMH\MyBlogBundle\Entity\User\Role;
use AMH\MyBlogBundle\Entity\User\User;
use AMH\MyBlogBundle\Entity\Blog\Post;
/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
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
	@param int Users count, greater then 0.
	@param Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface|null Password encoder, optional.
	
	@return array of persisted users.
	*/
	public function createUsers($c,\Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface $encoder=NULL){
		$c=(int)$c;
		if($c<1){
			throw new \InvalidArgumentException('Users count arg must be greater then 0, '.$c.' given');
		}
		$highestId=$this->getEntityManager()->getRepository('AMHMyBlogBundle:User\User')
			->createQueryBuilder('u')->select('max(u.id)')->getQuery()->getResult();
		if(isset($highestId[0][1])) $highestId=(int)$highestId[0][1];		
		if($highestId<0) $highestId=0;
		$users=array();
		for($i=($highestId+1),$count=$highestId+$c;$i<=$count;$i++){
			$user=new User();
			$user->setEmail('testuser'.$i.'@domain.com');
			$user->setName('Test User '.$i);
			$user->setPassword('12345');
			if($encoder){
				$user->setPassword($encoder->encodePassword($user->getPassword(),$user->getSalt()));
			}
			$this->getEntityManager()->persist($user);
			$users[]=$user;
		}
		$this->getEntityManager()->flush();
		return $users;
	}
	/**
	@param int Posts count.
	
	@return array of Post.
	*/
	public function createPosts($c){
		
	}
}
?>
