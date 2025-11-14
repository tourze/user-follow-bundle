# 用户关注功能 Bundle

[English](README.md) | [中文](README.zh-CN.md)

一个 Symfony Bundle，提供用户关注/粉丝关系管理功能，包含完整的事件处理和 EasyAdmin 集成。

## 功能特性

- **用户关注系统**: 支持关注/取消关注用户，包含状态管理
- **关注统计**: 获取粉丝数量和关注数量
- **事件系统**: 当用户关注/取消关注时触发事件
- **EasyAdmin 集成**: 内置管理后台界面，管理关注关系
- **Doctrine 集成**: 完整的数据库持久化，支持索引
- **创建者追踪**: 记录关系创建者和创建时间

## 安装

```bash
composer require tourze/user-follow-bundle
```

## 配置

在 `bundles.php` 中注册 Bundle：

```php
return [
    // ...
    Tourze\UserFollowBundle\UserFollowBundle::class => ['all' => true],
];
```

## 基本用法

### 使用关注服务

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

### 创建关注关系

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

### 事件处理

监听关注事件：

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

        // 发送通知、更新统计等
        // ...
    }
}
```

## 数据库结构

Bundle 会创建 `forum_follow_relation` 表，包含以下字段：

- `id`: 雪花 ID（主键）
- `user_id`: 关注者 ID（外键）
- `follow_user_id`: 被关注者 ID（外键）
- `status`: 布尔状态（true = 已关注，false = 已取消关注）
- `created_at`: 关系创建时间戳
- `updated_at`: 关系最后更新时间戳
- `created_by`: 关系创建者

## EasyAdmin 集成

Bundle 会自动注册管理后台控制器，用于在 EasyAdmin 后台管理关注关系。

## API 参考

### FollowService

- `isFollowing(UserInterface $follower, UserInterface $followedUser): bool`
  - 检查用户是否关注了另一个用户

- `getFansCount(UserInterface $user): int`
  - 获取用户的粉丝数量

- `getFollowCount(UserInterface $user): int`
  - 获取用户关注的数量

### FollowRelation 实体

- `getUser(): ?UserInterface`
  - 获取关注者

- `setUser(?UserInterface $user): void`
  - 设置关注者

- `getFollowUser(): ?UserInterface`
  - 获取被关注者

- `setFollowUser(?UserInterface $followUser): void`
  - 设置被关注者

- `getStatus(): ?bool`
  - 获取关注状态

- `setStatus(?bool $status): void`
  - 设置关注状态

## 事件

- `AfterFollowUser`: 用户关注另一个用户后触发
  - `getFollower()`: 返回发起关注的用户
  - `getFollowedUser()`: 返回被关注的用户

## 依赖

- Symfony 7.3+
- Doctrine ORM 3.0+
- EasyAdmin Bundle 4.0+
- Tourze Doctrine User Bundle
- Tourze Doctrine Snowflake Bundle
- Tourze Doctrine Timestamp Bundle

## 许可证

MIT License

## 贡献

欢迎贡献代码！请随时提交 Pull Request。