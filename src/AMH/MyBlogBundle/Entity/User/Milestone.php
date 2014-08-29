<?php
namespace AMH\MyBlogBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Milestone
 * @package AMH\MyBlogBundle\Entity\User
 * @author Alexander Horkun mindkilleralexs@gmail.com
 * @ORM\Entity
 * @ORM\Table(name="Milestone", uniqueConstraints={@ORM\UniqueConstraint(columns={"user", "type"})})
 */
class Milestone {
    const TYPE_POST_RATED="POST_RATED";
    const TYPE_POST_VIEWED="POST_VIEWED";

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string Milestone ID.
     * @ORM\Column(name="type", type="string", length=64, nullable=false)
     */
    private $type;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AMH\MyBlogBundle\Entity\User\User", fetch="EAGER", cascade={"all"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="time", nullable=false)
     */
    private $time;

    /**
     * @param string $type
     * @param User $u
     */
    public function  __construct($type, User $u){
        if($type){
            $this->setType($type);
        }
        if($u){
            $this->setUser($u);
        }
        $this->time=new \DateTime();
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \AMH\MyBlogBundle\Entity\User\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \AMH\MyBlogBundle\Entity\User\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
} 