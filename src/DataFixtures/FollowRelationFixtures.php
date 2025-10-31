<?php

namespace Tourze\UserFollowBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tourze\UserFollowBundle\Entity\FollowRelation;
use Tourze\UserServiceContracts\UserManagerInterface;

class FollowRelationFixtures extends Fixture
{
    public function __construct(
        private readonly ?UserManagerInterface $userManager = null,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // 使用 loadUserByIdentifier 方法来获取用户
        try {
            $user1 = $this->userManager?->loadUserByIdentifier('1');
            $user2 = $this->userManager?->loadUserByIdentifier('2');
        } catch (\Exception $e) {
            $user1 = null;
            $user2 = null;
        }

        // 如果没有找到用户，在测试环境中创建测试数据
        if (null === $user1 || null === $user2) {
            // 在测试环境中，我们只需要创建一些 FollowRelation 实体来满足测试需求
            // 这些实体会通过测试框架的 mock 机制来处理用户关系
            $relation1 = new FollowRelation();
            $relation1->setStatus(true); // 已关注
            $manager->persist($relation1);

            $relation2 = new FollowRelation();
            $relation2->setStatus(true); // 已关注
            $manager->persist($relation2);
        } else {
            $relation1 = new FollowRelation();
            $relation1->setUser($user1);
            $relation1->setFollowUser($user2);
            $relation1->setStatus(true); // 已关注
            $manager->persist($relation1);

            $relation2 = new FollowRelation();
            $relation2->setUser($user2);
            $relation2->setFollowUser($user1);
            $relation2->setStatus(true); // 互相关注
            $manager->persist($relation2);
        }

        $manager->flush();
    }
}
