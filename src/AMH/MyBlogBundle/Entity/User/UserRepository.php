<?php
namespace AMH\MyBlogBundle\Entity\User;
use AMH\MyBlogBundle\Entity\Blog\Post;

/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class UserRepository extends \Doctrine\ORM\EntityRepository{
	public function postsCount(User $u){
		
	}

    /**
     * @param User $user
     * @return array of data (posts_count, visited_count, rated_count).
     */
    public function userInfo(User $user){
        $qb=$this->createQueryBuilder('u');
        $qb->select('count(distinct p.id) as posts_count, count(distinct pv) as visited_count, count(distinct r) as rated_count')
            ->leftJoin('u.posts', 'p')->leftJoin('u.postsVisited', 'pv')->leftJoin('u.rates', 'r')
            ->andWhere($qb->expr()->eq('u.id',$user->getId()));
        return $qb->getQuery()->getArrayResult()[0];
    }

    /**
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function hasVisited(User $user, Post $post){
        $query=$this->createQueryBuilder('u');
        $query->select('count(pv) as result')->leftJoin('u.postsVisited','pv')
            ->andWhere($query->expr()->eq('u.id',$user->getId()))
            ->andWhere($query->expr()->eq('pv.id', $post->getId()));
        $result=(int)$query->getQuery()
            ->useResultCache(true, 60*60, 'user_'.$user->getId().'_visited_post_'.$post->getId())
            ->getSingleScalarResult();
        return (bool)$result;
    }
}
?>
