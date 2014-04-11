<?php
namespace AMH\MyBlogBundle\Entity\User;

use AMH\MyBlogBundle\Entity\Blog\Post;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/**
@author Alexander Horkun mindkilleralexs@gmail.com

@ORM\Entity
*/
class User{
	/**
	@var int
	
	@ORM\Id
	@ORM\Column(type="integer")
	@ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	/**
	@var Collection
	
	@ORM\OneToMany(targetEntity="AMH\MyBlogBundle\Entity\Blog\Post", mappedBy="author")
	*/
	protected $posts;
	
	public function __construct(){
		$this->posts=new Collection();
	}

    /**
     * Add posts
     *
     * @param \AMH\MyBlogBundle\Entity\Blog\Post $posts
     * @return User
     */
    public function addPost(\AMH\MyBlogBundle\Entity\Blog\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \AMH\MyBlogBundle\Entity\Blog\Post $posts
     */
    public function removePost(\AMH\MyBlogBundle\Entity\Blog\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts->toArray();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
