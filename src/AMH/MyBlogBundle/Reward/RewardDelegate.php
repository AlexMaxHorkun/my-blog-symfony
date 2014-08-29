<?php
namespace AMH\MyBlogBundle\Reward;

use AMH\MyBlogBundle\Entity\User\Milestone;

/**
* @author Alexander horkun mindkilleralexs@gmail.com
*/
class RewardDelegate implements RewardInterface{
    /**
     * @var RewardInterface[]
     */
    private $rewards;

    public function __construct(array $rewards=null){
        if($rewards){
            $this->setRewards($rewards);
        }
    }

    /**
     * @param \AMH\MyBlogBundle\Reward\RewardInterface[] $rewards
     */
    public function setRewards(array $rewards)
    {
        $this->rewards = $rewards;
    }

    /**
     * @return \AMH\MyBlogBundle\Reward\RewardInterface[]
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    public function isGrantedFor(Milestone $milestone){
        foreach($this->rewards as $r){
            if($r->isGrantedFor($milestone)){
                return true;
            }
        }
        return false;
    }

    public function award(Milestone $milestone){
        foreach($this->rewards as $r){
            if($r->isGrantedFor($milestone)){
                $r->award($milestone);
            }
        }
    }
} 