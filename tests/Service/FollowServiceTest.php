<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserFollowBundle\Entity\FollowRelation;
use Tourze\UserFollowBundle\Repository\FollowRelationRepository;
use Tourze\UserFollowBundle\Service\FollowService;

/**
 * @internal
 */
#[CoversClass(FollowService::class)]
final class FollowServiceTest extends TestCase
{
    private FollowService $service;

    private FollowRelationRepository $followRelationRepository;

    protected function setUp(): void
    {
        $this->followRelationRepository = $this->createMock(FollowRelationRepository::class);
        $this->service = new FollowService($this->followRelationRepository);
    }

    public function testGetFansCount(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->followRelationRepository->expects($this->once())
            ->method('count')
            ->with([
                'followUser' => $user,
                'status' => true,
            ])
            ->willReturn(5)
        ;

        $result = $this->service->getFansCount($user);

        $this->assertEquals(5, $result);
    }

    public function testGetFollowCount(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->followRelationRepository->expects($this->once())
            ->method('count')
            ->with([
                'user' => $user,
                'status' => true,
            ])
            ->willReturn(10)
        ;

        $result = $this->service->getFollowCount($user);

        $this->assertEquals(10, $result);
    }

    public function testIsFollowingReturnsTrueWhenRelationExists(): void
    {
        $follower = $this->createMock(UserInterface::class);
        $followedUser = $this->createMock(UserInterface::class);
        $relation = $this->createMock(FollowRelation::class);

        $this->followRelationRepository->expects($this->once())
            ->method('findOneBy')
            ->with([
                'user' => $follower,
                'followUser' => $followedUser,
                'status' => true,
            ])
            ->willReturn($relation)
        ;

        $result = $this->service->isFollowing($follower, $followedUser);

        $this->assertTrue($result);
    }

    public function testIsFollowingReturnsFalseWhenRelationDoesNotExist(): void
    {
        $follower = $this->createMock(UserInterface::class);
        $followedUser = $this->createMock(UserInterface::class);

        $this->followRelationRepository->expects($this->once())
            ->method('findOneBy')
            ->with([
                'user' => $follower,
                'followUser' => $followedUser,
                'status' => true,
            ])
            ->willReturn(null)
        ;

        $result = $this->service->isFollowing($follower, $followedUser);

        $this->assertFalse($result);
    }

    public function testGetFansCountWithZeroFans(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->followRelationRepository->expects($this->once())
            ->method('count')
            ->willReturn(0)
        ;

        $result = $this->service->getFansCount($user);

        $this->assertEquals(0, $result);
    }

    public function testGetFollowCountWithZeroFollows(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->followRelationRepository->expects($this->once())
            ->method('count')
            ->willReturn(0)
        ;

        $result = $this->service->getFollowCount($user);

        $this->assertEquals(0, $result);
    }
}
