# User Follow Bundle

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle that provides user follow/follower relationship management functionality with comprehensive event handling and EasyAdmin integration.

## Features

- **User Follow System**: Follow/unfollow users with status management
- **Follow Statistics**: Get followers count and following count
- **Event System**: Dispatch events when users follow/unfollow others
- **EasyAdmin Integration**: Built-in admin interface for managing follow relationships
- **Doctrine Integration**: Full database persistence with indexing
- **Blameable & Timestampable**: Track who created the relationship and when

## Installation

```bash
composer require tourze/user-follow-bundle
```

## Configuration

Add the bundle to your `bundles.php`:

```php
return [
    // ...
    Tourze\UserFollowBundle\UserFollowBundle::class => ['all' => true],
];
```

## Basic Usage

### Using the Follow Service

```php
<?php

use Tourze\UserFollowBundle\Service\FollowService;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    public function __construct(
        private FollowService $followService,
    ) {}

    public function checkFollowStatus(UserInterface $currentUser, UserInterface $targetUser): bool
    {
        return $this->followService->isFollowing($currentUser, $targetUser);
    }

    public function getUserStats(UserInterface $user): array
    {
        return [
            'followers' => $this->followService->getFansCount($user),
            'following' => $this->followService->getFollowCount($user),
        ];
    }
}
```

### Creating Follow Relations

```php
<?php

use Tourze\UserFollowBundle\Entity\FollowRelation;
use Doctrine\ORM\EntityManagerInterface;

class FollowManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function followUser(UserInterface $follower, UserInterface $followed): void
    {
        $relation = new FollowRelation();
        $relation->setUser($follower);
        $relation->setFollowUser($followed);
        $relation->setStatus(true);

        $this->em->persist($relation);
        $this->em->flush();
    }

    public function unfollowUser(UserInterface $follower, UserInterface $followed): void
    {
        $relation = $this->em->getRepository(FollowRelation::class)
            ->findOneBy([
                'user' => $follower,
                'followUser' => $followed,
                'status' => true,
            ]);

        if ($relation) {
            $relation->setStatus(false);
            $this->em->flush();
        }
    }
}
```

### Event Handling

Listen to follow events:

```php
<?php

use Tourze\UserFollowBundle\Event\AfterFollowUser;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class FollowNotificationListener
{
    #[AsEventListener(event: AfterFollowUser::class)]
    public function onAfterFollowUser(AfterFollowUser $event): void
    {
        $follower = $event->getFollower();
        $followedUser = $event->getFollowedUser();

        // Send notification, update statistics, etc.
        // ...
    }
}
```

## Database Schema

The bundle creates a `forum_follow_relation` table with the following structure:

- `id`: Snowflake ID (primary key)
- `user_id`: ID of the follower (foreign key)
- `follow_user_id`: ID of the followed user (foreign key)
- `status`: Boolean status (true = following, false = unfollowed)
- `created_at`: Timestamp when relation was created
- `updated_at`: Timestamp when relation was last updated
- `created_by`: User who created the relation

## EasyAdmin Integration

The bundle automatically registers admin controllers for managing follow relationships in the EasyAdmin backend.

## API Reference

### FollowService

- `isFollowing(UserInterface $follower, UserInterface $followedUser): bool`
  - Check if a user is following another user

- `getFansCount(UserInterface $user): int`
  - Get the number of followers for a user

- `getFollowCount(UserInterface $user): int`
  - Get the number of users that a user is following

### FollowRelation Entity

- `getUser(): ?UserInterface`
  - Get the follower

- `setUser(?UserInterface $user): void`
  - Set the follower

- `getFollowUser(): ?UserInterface`
  - Get the followed user

- `setFollowUser(?UserInterface $followUser): void`
  - Set the followed user

- `getStatus(): ?bool`
  - Get the follow status

- `setStatus(?bool $status): void`
  - Set the follow status

## Events

- `AfterFollowUser`: Dispatched after a user follows another user
  - `getFollower()`: Returns the user who initiated the follow
  - `getFollowedUser()`: Returns the user who was followed

## Dependencies

- Symfony 7.3+
- Doctrine ORM 3.0+
- EasyAdmin Bundle 4.0+
- Tourze Doctrine User Bundle
- Tourze Doctrine Snowflake Bundle
- Tourze Doctrine Timestamp Bundle

## License

MIT License

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.