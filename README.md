Users module
===============
Модуль пользователей для Bulldozer CMF

Установка
------------
Подключить в composer:
```
composer require bulldozer/users "*"
```

Добавить в backend\config\main.php:
```
return [
    'components' => [
        'menu' => [
            'class' => 'bulldozer\components\BackendMenu',
            'modules' => [
                'bulldozer\users\backend\Module'
            ]
        ],
    ],
    'modules' => [
        'users' => [
            'class' => 'bulldozer\users\backend\Module',
        ],
    ],
]
```

Добавить в frontend\config\main.php:
```
return [
    'modules' => [
        'users' => [
            'class' => 'bulldozer\users\frontend\Module',
        ],
    ],
]
```

Добавить в console\config\main.php:
```
return [
    'modules' => [
        'users' => [
            'class' => 'bulldozer\users\console\Module',
        ],
    ],
    'controllerMap' => [
        ...
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                ...
                'bulldozer\users\console\migrations',
                ...
            ],
        ],
        ...
    ],
]
```

Добавить в common\config\main.php:
```
return [
    ...
    'components' => [
        'authManager' => [
            'class' => 'bulldozer\users\rbac\DbManager',
        ],
    ],
    ...
];
```