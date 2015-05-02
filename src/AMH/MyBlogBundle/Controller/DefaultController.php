<?php

namespace AMH\MyBlogBundle\Controller;

use AMH\MyBlogBundle\Entity\Blog\Post;
use AMH\MyBlogBundle\Entity\User\Milestone;
use AMH\MyBlogBundle\Entity\User\User;
use AMH\MyBlogBundle\Entity\User\UserRepository;
use AMH\MyBlogBundle\Event\MilestoneEvent;
use AMH\MyBlogBundle\Service\UserInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure as JMSSecure;

/**
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class DefaultController extends Controller
{
    public function postsListAction($page = 1, $user)
    {
        $limit = 5;
        if ($user) {
            $user = $this->getDoctrine()->getManager()->find('AMHMyBlogBundle:User\User', (int)$user);
        }
        $repo = $this->getDoctrine()->getManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post');
        $qb = $repo->createQueryBuilder('p')->select('count(p)');
        if ($user) {
            $qb->where($qb->expr()->eq('p.author', $user->getId()));
        }
        $postCount = $qb->getQuery()->getResult();
        $postCount = (int)$postCount[0][1];
        $offset = ($page - 1) * $limit;
        if ($offset > $postCount) {
            $page = 1;
            $offset = 0;
        }
        $posts = array();
        if ($this->getRequest()->query->has('search')) {
            $sphix = $this->get('iakumai.sphinxsearch.search');
            $postsFound = $sphix->search($this->getRequest()->query->get('search'), array('amhmyblog_post'));
            print_r(array_keys($postsFound));
            die();
        } else {
            $posts = $qb->select('p')->setMaxResults($limit)->setFirstResult($offset)->orderBy(
                'p.createdTime',
                'DESC'
            )->getQuery()->getResult();
        }
        $ratings = $repo->averageRating($posts, true);
        $postsData = array();
        foreach ($posts as $p) {
            $postData = array(
                'id' => $p->getId(),
                'title' => $p->getTitle(),
                'text' => $p->getText(),
                'author' => array(
                    'name' => $p->getAuthor()->getName(),
                    'id' => $p->getAuthor()->getId(),
                ),
                'visits' => $p->getVisits(),
                'created' => $p->createdTime(),
                'rating' => 0,
            );
            foreach ($ratings as $rating) {
                if ($rating['post'] == $p->getId()) {
                    $postData['rating'] = $rating['rating'];
                    break;
                }
            }
            $postsData[] = $postData;
        }
        $pageCount = (int)($postCount / $limit);
        if ($postCount % $limit) {
            $pageCount++;
        }

        return $this->render(
            'AMHMyBlogBundle:Default:posts-list.html.twig',
            array(
                'posts' => $postsData,
                'text_length' => 120,
                'page' => $page,
                'page_count' => $pageCount
            )
        );
    }

    public function popularPostsBlockAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post');
        $mostVisited = $repo->mostVisited(5);
        /** @var \Memcache */
        $cache = $this->get('amh_my_blog_cache');
        $ratedHighest = unserialize($cache->get('amh_my_blog_rated_highest_posts'));
        if (!$ratedHighest) {
            $ratedHighest = $repo->ratedHighest(5);
            $cache->set('amh_my_blog_rated_highest_posts', serialize($ratedHighest), 0, 60 * 5);
        }
        $ratedHighestData = array();
        foreach ($ratedHighest as $pData) {
            $ratedHighestData[] = array(
                'id' => $pData[0]->getId(),
                'title' => $pData[0]->getTitle(),
                'rating' => $pData['ar']
            );
        }

        return $this->render(
            'AMHMyBlogBundle:Default:popular-posts-block.html.twig',
            array(
                'title_length' => 20,
                'most_visited' => $mostVisited,
                'rated_highest' => $ratedHighestData
            )
        );
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $viewData = array('form' => null, 'error' => null);
        $error = null;
        $user = new User();
        $form = $this->createForm('user_login', $user, array('action' => $this->generateUrl('login_check')));
        $form->add('login', 'submit')
            ->get('email')->setData($request->getSession()->get(SecurityContext::LAST_USERNAME));
        $viewData['form'] = $form->createView();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
            $request->getSession()->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        $viewData['error'] = $error;

        return $this->render(
            'AMHMyBlogBundle:Default:login.html.twig',
            $viewData
        );
    }

    public function registrationAction()
    {
        $viewData = array('form' => null, 'error' => null);
        $error = array();
        $user = new User();
        $form = $this->createForm(
            'user_registration',
            $user,
            array('validation_groups' => array('login', 'registration'))
        );
        $form->add('submit', 'submit');
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $user->setPassword(
                    $this->get('security.encoder_factory')->getEncoder($user)
                        ->encodePassword($user->getPassword(), $user->getSalt())
                );
            } catch (\Exception $e) {
                //do nothing, no specified encoder for user class
            }
            $role = $em->getRepository('AMHMyBlogBundle:User\Role')->findOneBy(array('role' => 'ROLE_USER'));
            if ($role) {
                $user->addRole($role);
            }
            try {
                $em->persist($user);
                $em->flush();
            } catch (\Exception $e) {
                die($e->getMessage());
            }
            if (!$error) {
                return $this->redirect($this->generateUrl('login'));
            }
        }
        $viewData['error'] = $error;
        $viewData['form'] = $form->createView();

        return $this->render(
            'AMHMyBlogBundle:Default:registration.html.twig',
            $viewData
        );
    }

    public function userInfoBlockAction()
    {
        $userInfo = array();
        $user = $this->getUser();
        if ($user) {
            /** @var UserInfoService $userInfoService */
            $userInfoService = $this->get('amh_my_blog.user_info_service');
            $userInfoEntry = $userInfoService->find($user);
            $userInfo['id'] = $userInfoEntry->getId();
            $userInfo['name'] = $userInfoEntry->getName();
            $userInfo['email'] = $userInfoEntry->getEmail();
            $userInfo['posts_count'] = $userInfoEntry->getPostsCount();
            $userInfo['visited_count'] = $userInfoEntry->getVisitedCount();
            $userInfo['rated_count'] = $userInfoEntry->getRatedCount();
        }

        return $this->render(
            'AMHMyBlogBundle:Default:user-info-block.html.twig',
            array('user' => $userInfo)
        );
    }

    public function postViewAction($id)
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->get('event_dispatcher');
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var UserInfoService $userInfoService */
        $userInfoService = $this->get('amh_my_blog.user_info_service');
        /** @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()->getManager()->getRepository('AMHMyBlogBundle:User\User');
        $ratingFormView = null;
        $repo = $this->getDoctrine()->getManager()->getRepository('AMHMyBlogBundle:Blog\Post');
        /** @var \AMH\MyBlogBundle\Entity\Blog\Post $post */
        $post = $repo->find($id);
        if (!$post) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        /** @var User $user */
        $user = $this->getUser();
        $rated = false;
        if ($user) {
            $user = $this->getDoctrine()->getManager()->merge($user);
            if (!$userRepo->hasVisited($user, $post)) {
                $userInfoService->incrVisitedCount($user);
            }
            $post->addVisitor($user);
            if (!$user->hasMilestone(Milestone::TYPE_POST_VIEWED)) {
                $viewedPostMilestone = new Milestone(Milestone::TYPE_POST_VIEWED, $user);
                try {
                    $user->addMilestone($viewedPostMilestone);
                    $entityManager->persist($viewedPostMilestone);
                    $entityManager->flush();
                    $eventDispatcher->dispatch(
                        MilestoneEvent::EVENT_MILESTONE_ACHIVED,
                        new MilestoneEvent($viewedPostMilestone)
                    );
                } catch (\Exception $ex) {
                    //user already has this milestone or smth else
                }
                unset($viewedPostMilestone);
            }
            $rated = $user->postRate($post);
            if (!$rated) {
                $ratingForm = $this->createForm('post_rating');
                $ratingForm->add('submit', 'submit');
                $ratingForm->handleRequest($this->getRequest());
                if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
                    $user->ratePost($post, $ratingForm->get('rating')->getData());
                    $rated = $ratingForm->get('rating')->getData();
                    if (!$user->hasMilestone(Milestone::TYPE_POST_RATED)) {
                        $ratedPostMilestone = new Milestone(Milestone::TYPE_POST_RATED, $user);
                        try {
                            $user->addMilestone($ratedPostMilestone);
                            $entityManager->persist($ratedPostMilestone);
                            $entityManager->flush();
                            $eventDispatcher->dispatch(
                                MilestoneEvent::EVENT_MILESTONE_ACHIVED,
                                new MilestoneEvent($ratedPostMilestone)
                            );
                        } catch (\Exception $ex) {
                            //user already has this milestone or smth else
                        }
                        unset($ratedPostMilestone);
                    }
                    $userInfoService->incrRatedCount($user);
                }
                $ratingFormView = $ratingForm->createView();
            } else {
                $rated = $rated->getRating();
            }
            $this->getDoctrine()->getManager()->flush();
        }
        $rating = $repo->averageRating(array($post));
        $postData = array(
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'text' => $post->getText(),
            'author' => array(
                'id' => $post->getAuthor()->getId(),
                'name' => $post->getAuthor()->getName(),
            ),
            'rating' => $rating[0]['rating'],
            'visits' => $post->getVisits(),
            'created' => $post->createdTime(),
            'is_rated' => (bool)count($post->getRates())
        );

        return $this->render(
            'AMHMyBlogBundle:Default:post-view.html.twig',
            array('post' => $postData, 'rating_form' => $ratingFormView, 'rated' => $rated)
        );
    }

    /**
     * @JMSSecure(roles="ROLE_USER")
     */
    public function postAddAction()
    {
        $user = $this->getUser();
        $post = new Post();
        $post->setAuthor($user);
        $form = $this->createForm('post_add', $post);
        $form->add('submit', 'submit');
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('amh_my_blog_post', array('id' => $post->getId())));
        }

        return $this->render(
            'AMHMyBlogBundle:Default:post-add.html.twig',
            array('form' => $form->createView())
        );
    }

    public function postRatedByAction(Post $post)
    {
        $ratingRepo = $this->get('doctrine')->getManager()->getRepository('AMHMyBlogBundle:Blog\Rate');
        $limit = 10;
        $ratedBy = $ratingRepo->findBy(array('post' => $post), array('id' => 'DESC'), $limit);

        return $this->render('AMHMyBlogBundle:Default:rated-by.html.twig', array('ratedBy' => $ratedBy));
    }
}
