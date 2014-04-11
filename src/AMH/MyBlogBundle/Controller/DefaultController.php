<?php

namespace AMH\MyBlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class DefaultController extends Controller
{
    public function postsListAction()
    {
    	$viewData=array('array'=>array('val1','val2','val3','val4'));
        return $this->render('AMHMyBlogBundle:Default:posts-list.html.twig', $viewData);
    }
}
