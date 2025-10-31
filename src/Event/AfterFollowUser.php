<?php

namespace Tourze\UserFollowBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Tourze\UserFollowBundle\Entity\FollowRelation;

/**
 * 关注用户后
 */
class AfterFollowUser extends Event
{
    private FollowRelation $followRelation;

    /**
     * @var array<string, mixed>
     */
    private array $result = [];

    /**
     * @return array<string, mixed>
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param array<string, mixed> $result
     */
    public function setResult(array $result): void
    {
        $this->result = $result;
    }

    public function getFollowRelation(): FollowRelation
    {
        return $this->followRelation;
    }

    public function setFollowRelation(FollowRelation $followRelation): void
    {
        $this->followRelation = $followRelation;
    }
}
