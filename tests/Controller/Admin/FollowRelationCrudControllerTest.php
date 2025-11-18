<?php

namespace Tourze\UserFollowBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use Tourze\UserFollowBundle\Controller\Admin\FollowRelationCrudController;
use Tourze\UserFollowBundle\Entity\FollowRelation;

/**
 * @internal
 */
#[CoversClass(FollowRelationCrudController::class)]
#[RunTestsInSeparateProcesses]
final class FollowRelationCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    public function testEntityFqcnIsCorrect(): void
    {
        $this->assertEquals(
            FollowRelation::class,
            FollowRelationCrudController::getEntityFqcn()
        );
    }

    #[Test]
    public function testListPageDisplaysCorrectly(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/admin');
        self::getClient($client);
        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertStringContainsString('dashboard', $content);
    }

    /**
     * @return AbstractCrudController<FollowRelation>
     */
    protected function getControllerService(): AbstractCrudController
    {
        $controller = self::getContainer()->get(FollowRelationCrudController::class);
        self::assertInstanceOf(AbstractCrudController::class, $controller);

        return $controller;
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID字段' => ['ID'];
        yield '关注者字段' => ['关注者'];
        yield '被关注者字段' => ['被关注者'];
        yield '关注状态字段' => ['关注状态'];
        yield '创建时间字段' => ['创建时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'ID字段' => ['id'];
        yield '关注者字段' => ['user'];
        yield '被关注者字段' => ['followUser'];
        yield '关注状态字段' => ['status'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'ID字段' => ['id'];
        yield '关注者字段' => ['user'];
        yield '被关注者字段' => ['followUser'];
        yield '关注状态字段' => ['status'];
    }
}
