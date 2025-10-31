<?php

namespace Tourze\UserFollowBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserFollowBundle\Repository\FollowRelationRepository;

/**
 * 用户关注关系服务
 * 封装用户关注相关的业务逻辑，避免跨模块直接调用Repository
 */
readonly class FollowService
{
    public function __construct(
        private FollowRelationRepository $followRelationRepository,
    ) {
    }

    /**
     * 获取用户的粉丝数量
     */
    public function getFansCount(UserInterface $user): int
    {
        return $this->followRelationRepository->count([
            'followUser' => $user,
            'status' => true,
        ]);
    }

    /**
     * 获取用户关注的数量
     */
    public function getFollowCount(UserInterface $user): int
    {
        return $this->followRelationRepository->count([
            'user' => $user,
            'status' => true,
        ]);
    }

    /**
     * 检查用户A是否关注了用户B
     */
    public function isFollowing(UserInterface $follower, UserInterface $followedUser): bool
    {
        $relation = $this->followRelationRepository->findOneBy([
            'user' => $follower,
            'followUser' => $followedUser,
            'status' => true,
        ]);

        return null !== $relation;
    }
}
