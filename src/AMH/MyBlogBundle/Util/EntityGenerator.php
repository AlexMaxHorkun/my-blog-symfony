<?php
namespace AMH\MyBlogBundle\Util;

use AMH\MyBlogBundle\Entity\Blog\Post;
use AMH\MyBlogBundle\Entity\User\Role;
use AMH\MyBlogBundle\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class EntityGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEntityManager($em);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param array of strings that will be used as Role objs role props.
     *
     * @return array of Role objs persisted
     */
    public function createRoles(array $roles)
    {
        $es = array();
        foreach ($roles as $r) {
            $role = new Role($r);
            $this->getEntityManager()->persist($role);
            $es[] = $role;
        }
        $this->getEntityManager()->flush();

        return $es;
    }

    /**
     * @param int Users count, greater then 0.
     * @param Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface|null Password encoder, optional.
     *
     * @return array of persisted users.
     */
    public function createUsers($c, \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface $encoder = null)
    {
        $c = (int)$c;
        if ($c < 1) {
            throw new \InvalidArgumentException('Users count arg must be greater then 0, ' . $c . ' given');
        }
        $highestId = $this->getEntityManager()->getRepository('AMHMyBlogBundle:User\User')
            ->createQueryBuilder('u')->select('max(u.id)')->getQuery()->getResult();
        if (isset($highestId[0])) {
            $highestId = (int)$highestId[0][1];
        }
        if (!$highestId) {
            $highestId = 0;
        }
        $users = array();

        for ($i = ($highestId + 1), $count = ($highestId + $c); $i <= $count; $i++) {
            $user = new User();
            $user->setEmail('testuser' . $i . '@domain.com');
            $user->setName('Test User ' . $i);
            $user->setPassword('12345');
            if ($encoder) {
                $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));
            }
            $this->getEntityManager()->persist($user);
            $users[] = $user;
        }
        $this->getEntityManager()->flush();

        return $users;
    }

    /**
     * @param int $c Posts count.
     *
     * @return array of Post.
     */
    public function createPosts($c)
    {
        $c = (int)$c;
        $posts = array();
        $users = $this->getEntityManager()->getRepository('AMH\MyBlogBundle\Entity\User\User')->findAll();
        $highestId = $this->getEntityManager()->getRepository('AMHMyBlogBundle:Blog\Post')
            ->createQueryBuilder('p')->select('max(p.id)')->getQuery()->getResult();
        if (isset($highestId[0])) {
            $highestId = (int)$highestId[0][1];
        }
        if ($highestId < 0) {
            $highestId = 0;
        }
        for ($i = $highestId + 1, $count = $c + $i; $i < $count; $i++) {
            $post = new Post();
            $post->setTitle(((rand(0, 1)) ? 'Some useless post' : 'Whining about something') . ' #' . $i);
            $post->setText('Generated text...end.');
            $post->setAuthor($users[rand(0, count($users) - 1)]);
            $posts[] = $post;
            $this->getEntityManager()->persist($post);
        }
        $this->getEntityManager()->flush();

        return $posts;
    }

    /**
     * @param int Count.
     * @param Post|null Post rated
     * protected function generateRates($c,$post=NULL){
     *
     * }
     * /**
     * One rate for each post by each user will be generated.
     *
     * @param bool|null $rs True if users can rate their own posts, false by default.
     * @param bool|null $cv Will create a visitation before adding rating if doesn't exist, true by default.
     *
     * @return array of Rate.
     */
    public function createRates($rs = false, $cv = true)
    {
        $rates = array();
        $users = $this->getEntityManager()->getRepository('AMH\MyBlogBundle\Entity\User\User')->findAll();
        $posts = $this->getEntityManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post')->findAll();
        foreach ($users as $u) {
            foreach ($posts as $p) {
                if ($u !== $p->getAuthor() && $rs || !$rs) {
                    if ($cv && !in_array($u, $p->getVisitors(), true)) {
                        $p->addVisitor($u);
                    }
                    $rate = $u->ratePost($p, rand(1, 100) / 100 * 5);
                    $this->getEntityManager()->persist($rate);
                    $rates[] = $rate;
                }
            }
        }
        $this->getEntityManager()->flush();

        return $rates;
    }

    /**
     * @param int|null $c Visits coutn to add for each post, if not provided a random count from 1 to 100 of visits will be added.
     *
     * @return null
     */
    public function addVisits($c = 0)
    {
        $posts = $this->getEntityManager()->getRepository('AMH\MyBlogBundle\Entity\Blog\Post')->findAll();
        foreach ($posts as $p) {
            if ($c) {
                $count = (int)$c;
            } else {
                $count = rand(1, 100);
            }
            $p->setVisits($p->getVisits() + $count);
        }
        $this->getEntityManager()->flush();
    }
}

?>
