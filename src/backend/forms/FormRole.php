<?php

namespace bulldozer\users\backend\forms;

use bulldozer\App;
use bulldozer\users\rbac\Role;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class FormRole
 * @package bulldozer\users\backend\models
 */
class FormRole extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $codename;

    /**
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public $permissions = [];

    /**
     * @var bool
     */
    public $isNewRecord;

    /**
     * @var Role
     */
    private $role;

    /**
     * FormRole constructor.
     * @param Role|null $role
     * @param array $config
     */
    public function __construct(Role $role = null, array $config = [])
    {
        if ($role !== null) {
            $this->role = $role;

            $this->name = $role->name;
            $this->codename = $role->codename;
            $this->description = $role->description;

            $auth = App::$app->authManager;
            $permissions = $auth->getPermissionsByRole($this->role->codename);
            $this->permissions = ArrayHelper::getColumn($permissions, 'codename');

            $this->isNewRecord = false;
        } else {
            $this->isNewRecord = true;
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'codename', 'description'], 'string'],
            [['name', 'codename'], 'required'],
            [
                'permissions',
                'each',
                'rule' => [
                    'in',
                    'range' => ArrayHelper::getColumn(App::$app->authManager->getPermissions(), 'codename')
                ]
            ],
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $auth = App::$app->authManager;

            if ($this->role === null) {
                $this->role = $auth->createRole($this->codename);
                $this->role->name = $this->name;
                $this->role->description = $this->name;
                $auth->add($this->role);
            } else {
                $old_name = $this->role->codename;

                $this->role->name = $this->name;
                $this->role->codename = $this->codename;
                $this->role->description = $this->description;

                $auth->update($old_name, $this->role);
            }

            $permissions = $auth->getPermissionsByRole($this->role->codename);

            foreach ($permissions as $permission) {
                $auth->removeChild($this->role, $permission);
            }

            foreach ($this->permissions as $permission) {
                $permission = $auth->getPermission($permission);
                $auth->addChild($this->role, $permission);
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'codename' => 'Кодовое имя',
            'description' => 'Описание',
            'permissions' => 'Разрешения',
        ];
    }
}