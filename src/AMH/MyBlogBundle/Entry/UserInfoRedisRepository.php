<?php
namespace AMH\MyBlogBundle\Entry;

use Predis\Client;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * userInfo repo that is using Redis.
 *
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class UserInfoRedisRepository implements UserInfoRepositoryInterface
{
    /**
     * @var Client
     */
    private $predis;

    /**
     * @var Serializer
     */
    private $serializer;

    private $keyTemplate = 'amhmyblog:user_info:';

    public function __construct(Client $client)
    {
        $this->predis = $client;
        $this->serializer = new Serializer(array(new GetSetMethodNormalizer()));
    }

    /**
     * @param int $id
     * @return UserInfo|null
     */
    public function find($id)
    {
        $data = $this->predis->hgetall($this->keyTemplate . $id);
        if ($data) {
            return $this->serializer->denormalize($data, 'AMH\MyBlogBundle\Entry\UserInfo');
        }

        return null;
    }

    /**
     * @param UserInfo $userInfo
     * @return void
     */
    public function persist(UserInfo $userInfo)
    {
        $this->predis->hmset($this->keyTemplate . $userInfo->getId(), $this->serializer->normalize($userInfo));
    }

    /**
     * @param UserInfo|int $userInfo ID or UserInfo entry.
     * @return void
     */
    public function delete($userInfo)
    {
        $this->predis->del(
            $this->keyTemplate . (($userInfo instanceof UserInfo) ? $userInfo->getId() : (int)$userInfo)
        );
    }

    public function incrVisitedCount($id)
    {
        if ($this->predis->exists($this->keyTemplate . $id)) {
            $this->predis->hincrby($this->keyTemplate . $id, 'visitedCount', 1);
        }
    }

    public function incrRatedCount($id)
    {
        if ($this->predis->exists($this->keyTemplate . $id)) {
            $this->predis->hincrby($this->keyTemplate . $id, 'ratedCount', 1);
        }
    }
} 