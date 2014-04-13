<?php
namespace AMH\MyBlogBundle\Entity\Blog;

use AMH\MyBlogBundle\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
/**
@author Alexander Horkun mindkilleralexs@gmail.com

@ORM\Entity(repositoryClass="AMH\MyBlogBundle\Entity\Blog\PostRepository")
@ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_post", columns={"title","author_id"})})
*/
class Post{
	/**
	@var int
	
	@ORM\Id
	@ORM\Column(type="integer")
	@ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	/**
	@var string
	
	@ORM\Column(type="string", length=50)
	*/
	protected $title="";
	/**
	@var string
	
	@ORM\Column(type="text", length=2500)
	*/
	protected $text="";
	/**
	@var User
	
	@ORM\ManyToOne(targetEntity="AMH\MyBlogBundle\Entity\User\User", inversedBy="posts")
	@ORM\JoinColumn(name="author_id")
	*/
	protected $author=NULL;
	/**
	@var \DateTime
	
	@ORM\Column(type="datetime")
	*/
	protected $createdTime;
	/**
	@var Collection of User.
	
	@ORM\ManyToMany(targetEntity="AMH\MyBlogBundle\Entity\User\User", inversedBy="postsVisited")
	*/
	protected $visitors;
	/**
	I should have created a class Visitation with date, user and post fields, but for simplicity lets leave it like it is.
	
	@var int Visits.
	
	@ORM\Column(type="integer")
	*/
	protected $visits=0;
	/**
	@var Collection of Rate.
	
	@ORM\OneToMany(targetEntity="AMH\MyBlogBundle\Entity\Blog\Rate", mappedBy="post")
	*/
	protected $rates;
	/**
	@param string|null $tt Subject.
	@param string|null $t Text.
	@param User|null $u Author.
	*/
	public function __construct($tt=NULL,$t=NULL,User $u=NULL){
		$this->visitors=new Collection();
		$this->rates=new Collection();
		$this->createdTime=new \DateTime();
		if($tt) $this->setTitle($tt);
	}

    /**
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set author
     *
     * @param \AMH\MyBlogBundle\Entity\user\User $author
     * @return Post
     */
    public function setAuthor(\AMH\MyBlogBundle\Entity\user\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AMH\MyBlogBundle\Entity\user\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Post
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
    /**
    @return array of User.
    */
    public function getVisitors(){
    	return $this->visitors->toArray();
    }
    
    public function addVisitor(User $u){
    	if(!$this->visitors->contains($u)){
    		$this->visitors[]=$u;
    	}
    	$this->visits++;
    }
    /**
    @return array of Rate.
    */
    public function getRates(){
    	return $this->rates->toArray();
    }
    
    public function addRate(Rate $r){
    	$this->rates[]=$r;
    }
    /**
    @return int
    */
    public function getVisits(){
    	return $this->visits;
    }
    
    public function addVisit(){
    	++$this->visits;
    }
    /**
    @param int
    */
    public function setVisits($c){
    	if($c<0){
    		throw new \InvalidArgumentException('Visits count cannot be less then zero');
    	}
    	$this->visits=(int)$c;
    }
    /**
    @return \DateTime
    */
    public function createdTime(){
    	return $this->createdTime;
    }
}
