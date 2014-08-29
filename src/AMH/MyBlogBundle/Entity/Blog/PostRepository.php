<?php
namespace AMH\MyBlogBundle\Entity\Blog;
use AMH\MyBlogBundle\Entity\User\User;

/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class PostRepository extends \Doctrine\ORM\EntityRepository{
	/**
	@param array|null $posts Array of IDs or Post objects, if given average ratings will be selected only for them.
	@param bool|null $selectIds If true resulting array will contain IDs not entities, false by default.
	
	@return array Of arrays with keys 'post' and 'rating'.
	*/
	public function averageRating($posts=array(),$selectIds=FALSE){
		foreach($posts as $key=>$p){
			if($p instanceof Post){
				if($p->getId()){
					$posts[$key]=$p->getId();
				}
				else{
					unset($posts[$key]);
				}
			}
			else{
				$posts[$key]=(int)$p;
			}
		}
		$qb=$this->createQueryBuilder('p');
		if($selectIds){
			$qb->select('p.id as post,avg(r.rating) as rating');
		}
		else{
			$qb->select('p as post,avg(r.rating) as rating');
		}
		$qb->leftJoin('p.rates','r')->groupBy('p');
		if($posts){
			$qb->where($qb->expr()->in('p.id',$posts));
		}
		return $qb->getQuery()->getResult();
	}
	/**
	Using doctrine cache for this.
	
	@param int $c Limit.
	
	@return array of Post.
	*/
	public function mostVisited($c){
		$c=(int)$c;
		if($c<1){
			throw new \InvalidArgumentException('Limit argument must be greater then 1, "'.$c.'" given');
		}
		$qb=$this->createQueryBuilder('p');
		$qb->select('p')->orderBy('p.visits','DESC')->setMaxResults($c);
		$query=$qb->getQuery();
		$query->useResultCache(TRUE,300,'most_visited_posts');
		$result=$query->getResult();
		return $result;
	}
	/**
	Using memcache for this one in the controller.
	
	@param int $c Limit.
	
	@return array of Post.
	*/
	public function ratedHighest($c){
		$c=(int)$c;
		if($c<1){
			throw new \InvalidArgumentException('Limit argument must be greater then 1, "'.$c.'" given');
		}
		$qb=$this->createQueryBuilder('p');
		$qb->select('p,avg(r.rating) as ar')->leftJoin('p.rates','r')->groupBy('r.post')->orderBy('ar','DESC')->setMaxResults($c);
		$query=$qb->getQuery();
		$result=$query->getResult();
		return $result;
	}

    /**
     * @param User $user
     * Doubles all user's posts' ratings.
     */
    public function doubleRates(User $user){
        $qb=$this->createQueryBuilder('p');
        $qb->update('AMH\MyBlogBundle\Entity\Blog\Rate', 'r')->set('r.rating','r.rating*2');
        $qb2=$this->createQueryBuilder('p');
        $qb2->select('p.id')->where($qb2->expr()->eq('p.author', $user->getId()));
        $qb->where($qb->expr()->in('r.post',$qb2->getDQL()));
        $qb->getQuery()->execute();
    }

    /**
     * @param User $user
     * Increments the number of all user's posts' views.
     */
    public function incrementViews(User $user){
        $qb=$this->createQueryBuilder('p');
        $qb->update('AMH\MybLogBundle\Entity\Blog\Post', 'p')->set('p.visits','p.visits+1')->where($qb->expr()->eq('p.author',$user->getId()));
        $qb->getQuery()->execute();
    }
}
?>
