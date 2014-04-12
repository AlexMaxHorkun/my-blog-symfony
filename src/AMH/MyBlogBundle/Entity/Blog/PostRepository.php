<?php
namespace AMH\MyBlogBundle\Entity\Blog;
/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class PostRepository extends \Doctrine\ORM\EntityRepository{
	/**
	@param Post|null If post entity given will return it's average rating, if null given will return all-posts : ave-rating array.
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
}
?>
