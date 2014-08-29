<?php
namespace AMH\MyBlogBundle\Reward;

use AMH\MyBlogBundle\Entity\User\Milestone;

/**
 * @author Alexander horkun mindkilleralexs@gmail.com
 */
interface RewardInterface {
    /**
     * @param Milestone $milestone
     * @return bool
     */
    public function isGrantedFor(Milestone $milestone);

    /**
     * @param Milestone $milestone
     * @return void
     */
    public function award(Milestone $milestone);
} 