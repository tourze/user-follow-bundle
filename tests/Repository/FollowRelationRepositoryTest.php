<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\UserFollowBundle\Entity\FollowRelation;
use Tourze\UserFollowBundle\Repository\FollowRelationRepository;

/**
 * @internal
 */
#[CoversClass(FollowRelationRepository::class)]
#[RunTestsInSeparateProcesses]
final class FollowRelationRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 没有特殊设置需求
    }

    /**
     * @return ServiceEntityRepository<FollowRelation>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(FollowRelationRepository::class);
    }

    protected function createNewEntity(): object
    {
        $user = $this->createNormalUser('follower-' . uniqid());
        $followUser = $this->createNormalUser('followed-' . uniqid());

        $entity = new FollowRelation();
        $entity->setUser($user);
        $entity->setFollowUser($followUser);
        $entity->setStatus(true);

        return $entity;
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $entity1 = new FollowRelation();
        $entity1->setStatus(true);

        $entity2 = new FollowRelation();
        $entity2->setStatus(false);

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($entity1);
        $entityManager->persist($entity2);
        $entityManager->flush();

        $repository = self::getService(FollowRelationRepository::class);
        $result = $repository->findOneBy([], ['status' => 'ASC']);

        $this->assertNotNull($result);
        $this->assertFalse($result->getStatus());
    }

    public function testFindByWithSpecificStatusShouldWork(): void
    {
        $entity1 = new FollowRelation();
        $entity1->setStatus(false);

        $entity2 = new FollowRelation();
        $entity2->setStatus(true);

        $entity3 = new FollowRelation();
        $entity3->setStatus(false);

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($entity1);
        $entityManager->persist($entity2);
        $entityManager->persist($entity3);
        $entityManager->flush();

        $repository = self::getService(FollowRelationRepository::class);
        $results = $repository->findBy(['status' => false]);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertFalse($result->getStatus());
        }
    }
}
