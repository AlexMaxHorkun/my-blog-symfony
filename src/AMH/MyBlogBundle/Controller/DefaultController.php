<?php

namespace AMH\MyBlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AMH\MyBlogBundle\Entity\User\User;
use Symfony\Component\Security\Core\SecurityContext;
/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class DefaultController extends Controller
{
    public function postsListAction($page=1)
    {
    	$limit=5;
    	$repo=$this->getDoctrine()->getManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post');
    	$qb=$repo->createQueryBuilder('p')->select('count(p)');
    	$postCount=$qb->getQuery()->getResult();
    	$postCount=(int)$postCount[0][1];
    	$offset=($page-1)*$limit;
    	if($offset>$postCount){
    		$page=1;
    		$offset=0;
    	}
    	$posts=$qb->select('p')->setMaxResults($limit)->setFirstResult($offset)->orderBy('p.createdTime')->getQuery()->getResult();
    	$ratings=$repo->averageRating($posts,TRUE);
    	$postsData=array();
    	foreach($posts as $p){
    		$postData=array(
    			'id'=>$p->getId(),
    			'title'=>$p->getTitle(),
    			'text'=>$p->getText(),
    			'author'=>array(
    				'name'=>$p->getAuthor()->getName(),
    				'id'=>$p->getAuthor()->getId(),
    			),
    			'visits'=>$p->getVisits(),
    			'created'=>$p->createdTime(),
    			'rating'=>0,
    		);
    		foreach($ratings as $rating){
    			if($rating['post']==$p->getId()){
    				$postData['rating']=$rating['rating'];
    				break;
    			}
    		}
    		$postsData[]=$postData;
    	}
    	$pageCount=(int)($postCount/$limit);
    	if($postCount % $limit) $pageCount++;
        return $this->render(
        	'AMHMyBlogBundle:Default:posts-list.html.twig',
        	array(
        		'posts'=>$postsData,
        		'text_length'=>120,
        		'page'=>$page,
        		'page_count'=>$pageCount
        	)
        );
    }
    
    public function popularPostsBlockAction(){
    	$repo=$this->getDoctrine()->getManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post');
    	$mostVisited=$repo->mostVisited(5);
    	$ratedHighest=$repo->ratedHighest(5);
    	$ratedHighestData=array();
    	foreach($ratedHighest as $pData){
    		$ratedHighestData[]=array(
    			'id'=>$pData[0]->getId(),
    			'title'=>$pData[0]->getTitle(),
    			'rating'=>$pData['ar']
    		);
    	}
    	return $this->render(
    		'AMHMyBlogBundle:Default:popular-posts-block.html.twig',
    		array(
    			'title_length'=>20,
    			'most_visited'=>$mostVisited,
    			'rated_highest'=>$ratedHighestData
    		)
    	);
    }
    
    public function loginAction(){
    	$request=$this->getRequest();
    	$viewData=array('form'=>NULL, 'error'=>NULL);
    	$error=NULL;
    	$user=new User();
    	$form=$this->createForm('user_login',$user,array('action'=>$this->generateUrl('login_check')));
    	$form->add('login','submit')
    		->get('email')->setData($request->getSession()->get(SecurityContext::LAST_USERNAME));
    	$viewData['form']=$form->createView();
    	if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error=$request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}
    	else{
    		$error=$request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
    		$request->getSession()->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}
    	$viewData['error']=$error;
    	return $this->render(
    		'AMHMyBlogBundle:Default:login.html.twig',
    		$viewData
    	);
    }
    
    public function registrationAction(){
    	$viewData=array('form'=>NULL, 'error'=>NULL);
    	$error=array();
    	$user=new User();
    	$form=$this->createForm('user_registration',$user,array('validation_groups'=>array('login','registration')));
    	$form->add('submit','submit');
    	$form->handleRequest($this->getRequest());
    	if($form->isSubmitted() && $form->isValid()){
    		$em=$this->getDoctrine()->getManager();
    		try{
    			$user->setPassword(
    				$this->get('security.encoder_factory')->getEncoder($user)
    					->encodePassword($user->getPassword(),$user->getSalt())
    			);
    		}
    		catch(\Exception $e){
    			//do nothing, no specified encoder for user class
    		}
    		$role=$em->getRepository('AMHMyBlogBundle:User\Role')->findOneBy(array('role'=>'ROLE_USER'));
    		if($role) $user->addRole($role);
    		try{
    			$em->persist($user);
    			$em->flush();
    		}
    		catch(\Exception $e){
    			die($e->getMessage());
    		}
    		if(!$error){
    			return $this->redirect($this->generateUrl('login'));
    		}
    	}
    	$viewData['error']=$error;
    	$viewData['form']=$form->createView();
    	return $this->render(
    		'AMHMyBlogBundle:Default:registration.html.twig',
    		$viewData
    	);
    }
    
    public function userInfoBlockAction(){
    	$userInfo=array();
    	$user=$this->getUser();
    	if($user){
    		$userInfo['name']=$user->getName();
    		$userInfo['email']=$user->getEmail();
    		$userInfo['posts_count']=count($user->getPosts());
    		$userInfo['visited_count']=count($user->getVisitedPosts());
    		$userInfo['rated_count']=count($user->getRates());
    		//$repo=$this->getDoctrine()->getManager()->getRepository('AMHMyBlogBundle:User\User');
    	}
    	return $this->render(
    		'AMHMyBlogBundle:Default:user-info-block.html.twig',
    		array('user'=>$userInfo)
    	);
    }
}
