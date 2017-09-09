<?php

namespace bulldozer\users\backend\forms;

use bulldozer\App;
use bulldozer\base\Form;
use bulldozer\users\enums\UserStatusEnum;
use bulldozer\users\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class FormUser
 * @package bulldozer\users\backend\forms
 */
class FormUser extends Form
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var int
     */
    public $status;

    /**
     * @var array
     */
    public $roles = [];

    /**
     * @var array
     */
    public $permissions = [];

    /**
     * @var bool
     */
    public $isNewRecord = true;

    /**
     * @var User
     */
    private $user;

    /**
     * FormUser constructor.
     * @param User|null $user
     * @param array $config
     */
    public function __construct(User $user = null, array $config = [])
    {
        if ($user !== null) {
            $this->user = $user;

            $this->setAttributes($user->getAttributes($this->getSavedAttributes()));

            $auth = Yii::$app->authManager;
            $roles = $auth->getRolesByUser($user->id);
            $permissions = $auth->getPermissionsByUser($user->id);

            $this->roles = ArrayHelper::getColumn($roles, 'codename');
            $this->permissions = ArrayHelper::getColumn($permissions, 'codename');

            $this->isNewRecord = false;
        } else {
            $this->user = new User();
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот почтовый ящик уже занят.'],

            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],

            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(UserStatusEnum::listData())],

            [
                'roles',
                'each',
                'rule' => [
                    'in',
                    'range' => ArrayHelper::getColumn(App::$app->authManager->getRoles(), 'codename')
                ]
            ],
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
        $transaction = Yii::$app->db->beginTransaction();

        if ($this->validate()) {
            $this->user->setAttributes($this->getAttributes($this->getSavedAttributes()));

            if (strlen($this->password) > 0) {
                $this->user->setPassword($this->password);
                $this->user->generateAuthKey();
            }

            if ($this->user->save()) {
                $auth = Yii::$app->authManager;

                $auth->revokeAll($this->user->id);

                if (is_array($this->roles)) {
                    foreach ($this->roles as $role) {
                        $role = $auth->getRole($role);
                        $auth->assign($role, $this->user->id);
                    }
                }

                if (is_array($this->permissions)) {
                    foreach ($this->permissions as $permission) {
                        $permission = $auth->getPermission($permission);
                        $auth->assign($permission, $this->user->id);
                    }
                }

                $transaction->commit();
                return true;
            }
        }

        $transaction->rollBack();
        return false;
    }

    /**
     * @return array
     */
    public function getSavedAttributes()
    {
        return [
            'email',
            'status',
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->user->id;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль',
            'roles' => 'Роли',
            'permissions' => 'Разрешения',
            'status' => 'Статус',
        ];
    }
}