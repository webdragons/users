<?php

namespace bulldozer\users\frontend;

use bulldozer\App;
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

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty(App::$app->i18n->translations['users'])) {
            App::$app->i18n->translations['users'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/../messages',
            ];
        }
    }

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