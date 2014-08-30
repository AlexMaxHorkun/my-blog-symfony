<?php
namespace AMH\MyBlogBundle\Service;

use AMH\MyBlogBundle\Entry\UserInfo;
use AMH\MyBlogBundle\Entry\UserInfoRepositoryInterface;
use AMH\MyBlogBundle\Entity\User\User;
use AMH\MyBlogBundle\Entity\User\UserRepository;

/**
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class UserInfoService {
    /**
     * @var UserInfoRepositoryInterface
     */
    private $repo;

    /** @var  UserRepository */
    private $userRepo;

    public function __construct(UserInfoRepositoryInterface $repo, UserRepository $userRepository){
        $this->repo=$repo;
        $this->userRepo=$userRepository;
    }

    /**
     * @param UserInfo $userInfo
     * @return void
     */
    public function persist(UserInfo $userInfo){
        $this->repo->persist($userInfo);
    }

    /**
     * @param int|User $user
     * @return UserInfo
     */
    public function find($user){
        $userInfo=$this->repo->find(($user instanceof User)? $user->getId() : (int)$user);
        if(!$userInfo){
            if(!($user instanceof User)){
                $user=$this->userRepo->find($user);
            }
            if($user){
                $userInfo=new UserInfo();
                $userInfo->setId($user->getId());
                $userInfo->setEmail($user->getEmail());
                $userInfo->setName($user->getName());
                $userInfoData=$this->userRepo->userInfo($user);
                $userInfo->setPostsCount($userInfoData['posts_count']);
                $userInfo->setRatedCount($userInfoData['rated_count']);
                $userInfo->setVisitedCount($userInfoData['visited_count']);
                $this->repo->persist($userInfo);
            }
        }
        return $userInfo;
    }

    /**
     * @param User|UserInfo|int $user
     */
    public function delete($user){
        if($user instanceof User){
            $id=$user->getId();
        }
        elseif($user instanceof UserInfo){
            $id=$user->getId();
        }
        else{
            $id=(int)$user;
        }
        $this->repo->delete($id);
    }

    /**
     * @param User|UserInfo|int $user
     * @return void
     */
    public function incrVisitedCount($user){
        if($user instanceof User){
            $id=$user->getId();
        }
        elseif($user instanceof UserInfo){
            $id=$user->getId();
        }
        else{
            $id=(int)$user;
        }
        $this->repo->incrVisitedCount($id);
        if($user instanceof UserInfo){
            $user->setVisitedCount($user->getVisitedCount()+1);
        }
    }

    /**
     * @param User|UserInfo|int $user
     * @return void
     */
    public function incrRatedCount($user){
        if($user instanceof User){
            $id=$user->getId();
        }
        elseif($user instanceof UserInfo){
            $id=$user->getId();
        }
        else{
            $id=(int)$user;
        }
        $this->repo->incrRatedCount($id);
        if($user instanceof UserInfo){
            $user->setVisitedCount($user->getVisitedCount()+1);
        }
    }
} 