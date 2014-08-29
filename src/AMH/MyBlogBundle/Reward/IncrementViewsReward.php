<?php
namespace AMH\MyBlogBundle\Reward;

use AMH\MyBlogBundle\Entity\Blog\PostRepository;
use AMH\MyBlogBundle\Entity\User\Milestone;

/**
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class IncrementViewsReward implements RewardInterface{
    /**
     * @var PostRepository
     */
    private $postRepo;

    public function __construct(PostRepository $postRepository){
        $this->postRepo=$postRepository;
    }

    public function isGrantedFor(Milestone $milestone){
        if($milestone->getType()==Milestone::TYPE_POST_VIEWED) return true;
    }

    public function award(Milestone $milestone){
        $this->postRepo->incrementViews($milestone->getUser());
    }
} 