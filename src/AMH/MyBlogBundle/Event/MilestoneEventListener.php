<?php
namespace AMH\MyBlogBundle\Event;

use AMH\MyBlogBundle\Reward\RewardDelegate;

class MilestoneEventListener
{
    /**
     * @var RewardDelegate
     */
    private $rewardDelegate;

    public function __construct(RewardDelegate $rewardDelegate)
    {
        $this->rewardDelegate = $rewardDelegate;
    }

    public function onMilestoneAchieved(MilestoneEvent $event)
    {
        $this->rewardDelegate->award($event->getMilestone());
    }
} 