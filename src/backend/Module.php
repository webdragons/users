<?php

namespace bulldozer\users\backend;

use bulldozer\App;
use bulldozer\base\BackendModule;

class Module extends BackendModule
{
    public $defaultRoute = 'default';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'bulldozer\users\backend\controllers';

    /**
     * @inheritdoc
     */
    public function getMenuItems(): array
    {
        $controllerId = isset(App::$app->controller) ? App::$app->controller->id : '';
        $moduleId = isset(App::$app->controller->module) ? App::$app->controller->module->id : '';

        return [
            [
                'label' => 'Пользователи',
                'icon' => 'fa fa-users',
                'child' => [
                    [
                        'label' => 'Список',
                        'icon' => 'fa fa-users',
                        'url' => ['/users'],
                        'rules' => ['users_manage'],
                        'active' => $moduleId == 'users' && $controllerId == 'default',
                    ],
                    [
                        'label' => 'Роли',
                        'icon' => 'fa fa-fire-extinguisher',
                        'url' => ['/users/roles'],
                        'rules' => ['roles_manage'],
                        'active' => $moduleId == 'users' && $controllerId == 'roles',
                    ],
                ]
            ]
        ];
    }

    /*
     * @inheritdoc
     */
    public function createController($route)
    {
        $validRoutes = [$this->defaultRoute, 'roles'];
        $isValidRoute = false;

        foreach ($validRoutes as $validRoute) {
            if (strpos($route, $validRoute) === 0) {
                $isValidRoute = true;
                break;
            }
        }

        return (empty($route) or $isValidRoute)
            ? parent::createController($route)
            : parent::createController("{$this->defaultRoute}/{$route}");
    }

    /*
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $action->controller->view->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['/users']];
        return parent::beforeAction($action);
    }
}