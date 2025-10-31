<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\UserFollowBundle\UserFollowBundle;

/**
 * UserFollow Bundle 测试
 *
 * @internal
 */
#[CoversClass(UserFollowBundle::class)]
#[RunTestsInSeparateProcesses]
final class UserFollowBundleTest extends AbstractBundleTestCase
{
}
