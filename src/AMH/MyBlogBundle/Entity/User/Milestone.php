<?php
namespace AMH\MyBlogBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Milestone
 * @package AMH\MyBlogBundle\Entity\User
 * @author Alexander Horkun mindkilleralexs@gmail.com
 * @ORM\Entity
 * @ORM\Table(name="UsersMilestone", uniqueConstraints={@ORM\UniqueConstraint(columns={"user_id", "type"})})
 */
class Milestone
{
    const TYPE_POST_RATED = "POST_RATED";
    const TYPE_POST_VIEWED = "POST_VIEWED";

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    protected $id;

    /**
     * @var string Milestone ID.
     * @ORM\Column(name="type", type="string", length=64, nullable=false)
     */
    protected $type;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AMH\MyBlogBundle\Entity\User\User", fetch="EAGER", cascade={"persist", "remove"}, inversedBy="milestones")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="time", nullable=false)
     */
    protected $time;

    /**
     * @param string $type
     * @param User $u
     */
    public function  __construct($type, User $u)
    {
        if ($type) {
            $this->setType($type);
        }
        if ($u) {
            $this->setUser($u);
        }
        $this->time = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \AMH\MyBlogBundle\Entity\User\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \AMH\MyBlogBundle\Entity\User\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
} 