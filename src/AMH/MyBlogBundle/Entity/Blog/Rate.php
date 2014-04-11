<?php
namespace AMH\MyBlogBundle\Entity\Blog;

use AMH\MyBlogBundle\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/**
@author Alexander Horkun mindkilleralexs@gmail.com

@ORM\Entity
*/
class Rate{
	/**
	@var int
	
	@ORM\Id
	@ORM\Column(type="integer")
	@ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id=0;
	/**
	@var User
	
	@ORM\ManyToOne(targetEntity="AMH\MyBlogBundle\Entity\User\User", inversedBy="rates")
	@ORM\JoinColumn(name="user_id")
	*/
	protected $by=NULL;
	/**
	@var Post
	
	@ORM\ManyToOne(targetEntity="AMH\MyBlogBundle\Entity\Blog\Post", inversedBy="rates")
	@ORM\JoinColumn(name="post_id")
	*/
	protected $post=NULL;
	/**
	@var float
	
	@ORM\Column(type="float")
	*/
	protected $rating=0;
	/**
	@param float|null $r Rating.
	*/
	public function __construct($r=NULL,User $b=NULL,Post $p=NULL){
		if($r) $this->setRating($r);
		if($b) $this->setBy($b);
		if($p) $this->setPost($p);
	}
	/**
	@return int
	*/
	public function getId(){
		return $this->id;
	}
	/**
	@return float
	*/
	public function getRating(){
		return $this->rating;
	}
	/**
	@param float
	*/
	public function setRating($r){
		$r=(float)$r;
		$this->rating=$r;
	}
}
