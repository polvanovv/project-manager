<?php


namespace App\Menu\Work;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SettingsMenu
{
    private $factory;
    private $auth;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    public function build(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav nav-tabs mb-4']);

        if ($this->auth->isGranted('ROLE_WORK_MANAGE_PROJECTS')) {
            $menu
                ->addChild('Common', [
                    'route' => 'work_projects_project_settings',
                    'routeParameters' => ['project_id' => $options['project_id']]
                ])
                ->setExtra('routes', [
                    ['route' => 'work_projects_project_settings'],
                    ['route' => 'work_projects_project_settings_edit'],
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');

            $menu
                ->addChild('Departments', [
                    'route' => 'work_projects_project_settings_departments',
                    'routeParameters' => ['project_id' => $options['project_id']]
                ])
                ->setExtra('routes', [
                    ['route' => 'work_projects_project_settings_departments'],
                    ['pattern' => '/^work_projects_project_settings_departments_.+/']
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }
}