<?php


namespace App\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SidebarMenu
{
    /**
     * @var FactoryInterface
     */
    private $factory;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'c-sidebar-nav']);

        $menu->addChild('Dashboard', ['route' => 'home'])
            ->setExtra('icon', 'c-sidebar-nav-icon icon-speedometer')
            ->setAttribute('class', 'c-sidebar-nav-item')
            ->setLinkAttribute('class', 'c-sidebar-nav-link');

        $menu->addChild('Work')->setAttribute('class', 'c-sidebar-nav-label');

        $menu->addChild('Projects', ['route' => 'work_projects'])
            ->setExtra('routes', [
                ['route' => 'work_projects'],
                ['pattern' => '/^work_projects_.+/']
            ])
            ->setExtra('icon', 'c-sidebar-nav-icon icon-briefcase')
            ->setAttribute('class', 'c-sidebar-nav-item')
            ->setLinkAttribute('class', 'c-sidebar-nav-link');

        if ($this->auth->isGranted('ROLE_WORK_MANAGE_MEMBERS')) {
            $menu->addChild('Members', ['route' => 'work_members'])
                ->setExtra('routes', [
                    ['route' => 'work_members'],
                    ['pattern' => '/^work_members_.*/']
                ])
                ->setExtra('icon', 'c-sidebar-nav-icon icon-people')
                ->setAttribute('class', 'c-sidebar-nav-item')
                ->setLinkAttribute('class', 'c-sidebar-nav-link');
        }

        $menu->addChild('Control')->setAttribute('class', 'c-sidebar-nav-label');

        if ($this->auth->isGranted('ROLE_MANAGE_USERS')) {
            $menu->addChild('Users', ['route' => 'users'])
                ->setExtra('routes', [
                    ['route' => 'users'],
                    ['pattern' => '/^users_.*/']
                ])
                ->setExtra('icon', 'c-sidebar-nav-icon icon-people')
                ->setAttribute('class', 'c-sidebar-nav-item')
                ->setLinkAttribute('class', 'c-sidebar-nav-link');
        }

        $menu->addChild('Profile', ['route' => 'profile'])
            ->setExtra('routes', [
                ['route' => 'profile'],
                ['pattern' => '/^profile_.*/']
            ])
            ->setExtra('icon', 'c-sidebar-nav-icon icon-people')
            ->setAttribute('class', 'c-sidebar-nav-item')
            ->setLinkAttribute('class', 'c-sidebar-nav-link');

        return $menu;
    }
}