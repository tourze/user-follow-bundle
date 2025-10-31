<?php

namespace Tourze\UserFollowBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Tourze\UserFollowBundle\Entity\FollowRelation;

/**
 * @extends AbstractCrudController<FollowRelation>
 */
#[AdminCrud(routePath: '/user_follow/follow_relation', routeName: 'user_follow_follow_relation')]
final class FollowRelationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FollowRelation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('ID'),
            AssociationField::new('user', '关注者'),
            AssociationField::new('followUser', '被关注者'),
            ChoiceField::new('status', '关注状态')
                ->setChoices([
                    '已取消关注' => 0,
                    '已关注' => 1,
                ]),
            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('user', '关注者'))
            ->add(EntityFilter::new('followUser', '被关注者'))
            ->add(ChoiceFilter::new('status', '关注状态')->setChoices([
                '已取消关注' => 0,
                '已关注' => 1,
            ]))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('关注关系')
            ->setEntityLabelInPlural('关注关系')
            ->setPageTitle('index', '关注关系列表')
            ->setPageTitle('new', '创建关注关系')
            ->setPageTitle('edit', '编辑关注关系')
            ->setPageTitle('detail', '关注关系详情')
        ;
    }
}
