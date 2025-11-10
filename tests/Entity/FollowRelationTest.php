<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\UserFollowBundle\Entity\FollowRelation;

/**
 * 关注关系实体测试
 *
 * @internal
 */
#[CoversClass(FollowRelation::class)]
final class FollowRelationTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new FollowRelation();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        // user 和 followUser 属性已经有专门的测试方法
        // 避免在 DataProvider 中使用 UserInterface Mock 导致序列化问题
        yield 'status' => ['status', true];
    }

    public function testFollowRelationShouldBeInstantiable(): void
    {
        $relation = new FollowRelation();

        $this->assertInstanceOf(FollowRelation::class, $relation);
    }

    public function testUserRelationShouldWork(): void
    {
        $relation = new FollowRelation();
        $user = $this->createMock(UserInterface::class);

        $relation->setUser($user);

        $this->assertSame($user, $relation->getUser());
    }

    public function testFollowUserRelationShouldWork(): void
    {
        $relation = new FollowRelation();
        $user = $this->createMock(UserInterface::class);

        $relation->setFollowUser($user);

        $this->assertSame($user, $relation->getFollowUser());
    }

    public function testStatusShouldWork(): void
    {
        $relation = new FollowRelation();

        $relation->setStatus(true);

        $this->assertTrue($relation->getStatus());
    }

    public function testToStringShouldReturnStringRepresentation(): void
    {
        $relation = new FollowRelation();

        $result = (string) $relation;

        $this->assertNotEmpty($result);
    }
}
