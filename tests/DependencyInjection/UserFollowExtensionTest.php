<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\UserFollowBundle\DependencyInjection\UserFollowExtension;

/**
 * UserFollow 扩展测试
 *
 * @internal
 */
#[CoversClass(UserFollowExtension::class)]
final class UserFollowExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testExtensionAlias(): void
    {
        $extension = new UserFollowExtension();
        $this->assertEquals('user_follow', $extension->getAlias());
    }
}
