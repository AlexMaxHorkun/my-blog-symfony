<?php
namespace AMH\MyBlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AMH\MyBlogBundle\Entity\User\Milestone;

class MilestoneEvent extends Event{
    const EVENT_MILESTONE_ACHIVED="amh_my_blog.milestone_achieved";

    /**
     * @var Milestone
     */
    private $milestone;

    public function __construct(Milestone $milestone){
        $this->milestone=$milestone;
    }

    /**
     * @return \AMH\MyBlogBundle\Entity\User\Milestone
     */
    public function getMilestone()
    {
        return $this->milestone;
    }


} 