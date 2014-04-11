<?php
namespace AMH\MyBlogBundle\Entity\User;

use AMH\MyBlogBundle\Entity\Blog\Post;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as Collection;
use Symfony\Component\Security\Core\User\UserInterface;
/**
@author Alexander Horkun mindkilleralexs@gmail.com

@ORM\Entity
*/
class User implements UserInterface, \Serializable{
	/**
	@var int
	
	@ORM\Id
	@ORM\Column(type="integer")
	@ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	/**
	@var string Email.
	
	@ORM\Column(type="string", unique=true)
	*/
	protected $email;
	/**
	@var string
	
	@ORM\Column(type="string", unique=true)
	*/
	protected $name;
	/**
	@var string
	
	@ORM\Column(type="string")
	*/
	protected $password;
	/**
	@var Collection
	
	@ORM\ManyToMany(targetEntity="AMH\MyBlogBundle\Entity\User\Role", inversedBy="users")
	*/
	protected $roles;
	/**
	@var Collection
	
	@ORM\OneToMany(targetEntity="AMH\MyBlogBundle\Entity\Blog\Post", mappedBy="author")
	*/
	protected $posts;
	
	public function __construct(){
		$this->posts=new Collection();
		$this->roles=new Collection();
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
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
       $roles=$this->roles->toArray();
       if(!$roles) $roles=array(new Role('ROLE_USER'));
       return $roles;
    }
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Add roles
     *
     * @param \AMH\MyBlogBundle\Entity\User\Role $roles
     * @return User
     */
    public function addRole(Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \AMH\MyBlogBundle\Entity\User\Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    
    public function serialize(){
    	return serialize(array($this->id,$this->email,$this->name,$this->password));
    }
    
    public function unserialize($data){
    	list($this->id,$this->email,$this->name,$this->password)=unserialize($data);
    }
}
