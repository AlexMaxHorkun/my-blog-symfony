<?php
namespace AMH\MyBlogBundle\Entry;

/**
 * Repository interface used to manage UserInfo entries.
 *
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
interface UserInfoRepositoryInterface
{
    /**
     * @param int $id
     * @return UserInfo
     */
    public function find($id);

    /**
     * @param UserInfo $userInfo
     * @return void
     */
    public function persist(UserInfo $userInfo);

    /**
     * @param UserInfo|int $userInfo ID or UserInfo entry.
     * @return void
     */
    public function delete($userInfo);

    /**
     * @param int $id
     * @return void
     */
    public function incrVisitedCount($id);

    /**
     * @param int $id
     * @return void
     */
    public function incrRatedCount($id);
} 