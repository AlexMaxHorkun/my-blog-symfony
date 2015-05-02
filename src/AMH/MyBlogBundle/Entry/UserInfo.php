<?php
namespace AMH\MyBlogBundle\Entry;

/**
 * User data received from NoSQL storage.
 *
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class UserInfo
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * Written posts count.
     *
     * @var int
     */
    private $postsCount;

    /**
     * Visited posts count.
     *
     * @var int
     */
    private $visitedCount;

    /**
     * Number of ratings posted.
     *
     * @var int
     */
    private $ratedCount;

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $postsCount
     */
    public function setPostsCount($postsCount)
    {
        $this->postsCount = $postsCount;
    }

    /**
     * @return int
     */
    public function getPostsCount()
    {
        return $this->postsCount;
    }

    /**
     * @param int $ratedCount
     */
    public function setRatedCount($ratedCount)
    {
        $this->ratedCount = $ratedCount;
    }

    /**
     * @return int
     */
    public function getRatedCount()
    {
        return $this->ratedCount;
    }

    /**
     * @param int $visitedCount
     */
    public function setVisitedCount($visitedCount)
    {
        $this->visitedCount = $visitedCount;
    }

    /**
     * @return int
     */
    public function getVisitedCount()
    {
        return $this->visitedCount;
    }


} 