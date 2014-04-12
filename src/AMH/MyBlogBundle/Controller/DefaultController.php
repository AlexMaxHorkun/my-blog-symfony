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
    public function postsListAction()
    {
    	//$posts=$this->getDoctrine()->getManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post')->findAll();
    	$posts=$this->getDoctrine()->getManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post')->averageRating();
    	$postsData=array();
    	foreach($posts as $pData){
    		$postsData[]=array(
    			'id'=>$pData[0]->getId(),
    			'title'=>$pData[0]->getTitle(),
    			'text'=>$pData[0]->getText(),
    			'author'=>array(
    				'name'=>$pData[0]->getAuthor()->getName(),
    				'id'=>$pData[0]->getAuthor()->getId(),
    			),
    			'rating'=>$pData[1],
    			'visits'=>$pData[0]->getVisits()
    		);
    	}
        return $this->render('AMHMyBlogBundle:Default:posts-list.html.twig', array('posts'=>$postsData, 'text_length'=>120));
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
}
