<?php
namespace AMH\MyBlogBundle\Reward;

use AMH\MyBlogBundle\Entity\Blog\PostRepository;
use AMH\MyBlogBundle\Entity\User\Milestone;

/**
 * @author Alexander horkun mindkilleralexs@gmail.com
 */
class DoubleRatesReward implements RewardInterface{
    /**
     * @var PostRepository
     */
    private $postRepo;

    public function __construct(PostRepository $postRepository){
        $this->postRepo=$postRepository;
    }

    public function isGrantedFor(Milestone $milestone){
        if($milestone->getType()==Milestone::TYPE_POST_RATED) return true;
    }

    public function award(Milestone $milestone){
        $this->postRepo->doubleRates($milestone->getUser());
    }
} 