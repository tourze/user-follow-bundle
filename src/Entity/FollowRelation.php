<?php

namespace Tourze\UserFollowBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\UserFollowBundle\Repository\FollowRelationRepository;

#[ORM\Entity(repositoryClass: FollowRelationRepository::class)]
#[ORM\Table(name: 'forum_follow_relation', options: ['comment' => '用户关注表'])]
#[ORM\UniqueConstraint(name: 'forum_follow_relation_idx_uniq', columns: ['user_id', 'follow_user_id'])]
class FollowRelation implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL', options: ['comment' => '关注者'])]
    private ?UserInterface $user = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL', options: ['comment' => '被关注者'])]
    private ?UserInterface $followUser = null;

    #[IndexColumn]
    #[Assert\NotNull]
    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '关注状态，false已取消关注，true已关注'])]
    private ?bool $status = null;

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    public function getFollowUser(): ?UserInterface
    {
        return $this->followUser;
    }

    public function setFollowUser(?UserInterface $followUser): void
    {
        $this->followUser = $followUser;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function __toString(): string
    {
        return sprintf('%s #%s', 'FollowRelation', $this->id ?? 'new');
    }
}
