<?php

namespace bulldozer\users\frontend;

use bulldozer\base\FrontendModule;

class Module extends FrontendModule
{
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'default';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'bulldozer\users\frontend\controllers';

    /*
     * @inheritdoc
     */
    public function createController($route)
    {
        $validRoutes = [$this->defaultRoute];
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
}