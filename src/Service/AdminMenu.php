<?php

declare(strict_types=1);

namespace Tourze\UserFollowBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\UserFollowBundle\Entity\FollowRelation;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('用户管理')) {
            $item->addChild('用户管理');
        }

        $userManagementItem = $item->getChild('用户管理');
        if (null !== $userManagementItem) {
            $userManagementItem
                ->addChild('关注关系')
                ->setUri($this->linkGenerator->getCurdListPage(FollowRelation::class))
                ->setAttribute('icon', 'fas fa-users')
            ;
        }
    }
}
