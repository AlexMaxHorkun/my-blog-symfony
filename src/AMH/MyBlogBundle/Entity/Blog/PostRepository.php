<?php
namespace AMH\MyBlogBundle\Entity\Blog;
/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class PostRepository extends \Doctrine\ORM\EntityRepository{
	/**
	@param Post|null If post entity given will return it's average rating, if null given will return all-posts : ave-rating array.
	
	@return array
	*/
	public function averageRating(Post $p=NULL){
		if($p && !$p->getId()){
			throw new\InvalidArgumentException('Post argument must have ID');
		}
		$qb=$this->createQueryBuilder('p');
		$qb->select('p,avg(r.rating)')->leftJoin('p.rates','r')->groupBy('r.post');
		if($p){
			$qb->where($qb->expr()->eq('r.post',$p->getId()));
		}
		return $qb->getQuery()->getResult();
	}
	/**
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
		return $qb->getQuery()->getResult();
	}
	/**
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
		return $qb->getQuery()->getResult();
	}
}
?>
