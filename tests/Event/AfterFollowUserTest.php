<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Tests\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractEventTestCase;
use Tourze\UserFollowBundle\Entity\FollowRelation;
use Tourze\UserFollowBundle\Event\AfterFollowUser;

/**
 * 关注用户后事件测试
 *
 * @internal
 */
#[CoversClass(AfterFollowUser::class)]
final class AfterFollowUserTest extends AbstractEventTestCase
{
    public function testConstructorWorksWithoutParameters(): void
    {
        $event = new AfterFollowUser();
        $this->assertInstanceOf(AfterFollowUser::class, $event);
    }

    public function testFollowRelationGetterAndSetter(): void
    {
        $event = new AfterFollowUser();
        /*
         * Mock FollowRelation Entity的原因：
         * 1. Entity类通常没有对应的接口，直接以数据模型实体存在
         * 2. 测试需要控制Entity的属性和方法行为，避免数据库依赖
         * 3. 使用Entity具体类Mock是单元测试的标准做法
         */
        $followRelation = $this->createMock(FollowRelation::class);

        $event->setFollowRelation($followRelation);
        $this->assertSame($followRelation, $event->getFollowRelation());
    }

    public function testResultGetterAndSetter(): void
    {
        $event = new AfterFollowUser();
        $result = ['key' => 'value'];

        $event->setResult($result);
        $this->assertSame($result, $event->getResult());
    }

    public function testResultDefaultValue(): void
    {
        $event = new AfterFollowUser();
        $this->assertEmpty($event->getResult());
    }
}
