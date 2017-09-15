<?php
namespace bulldozer\users\models;

use bulldozer\users\enums\UserStatusEnum;
use bulldozer\App;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $statusName
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName() : string
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() : array
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() : array
    {
        return [
            [['password_hash', 'password_reset_token', 'email', 'auth_key'], 'string'],

            [['created_at', 'updated_at'], 'integer'],

            ['status', 'default', 'value' => UserStatusEnum::STATUS_ACTIVE],
            ['status', 'in', 'range' => [UserStatusEnum::STATUS_ACTIVE, UserStatusEnum::STATUS_DELETED]],
        ];
    }

    /**
     * @return string
     */
    public function getStatusName() : string
    {
        return UserStatusEnum::getLabel($this->status);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) : ?User
    {
        return static::findOne(['id' => $id, 'status' => UserStatusEnum::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email) : ?User
    {
        return static::findOne(['email' => $email, 'status' => UserStatusEnum::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token) : ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => UserStatusEnum::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token) : bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = App::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() : ?int
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() : ?string
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) : bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password) : bool
    {
        return App::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password) : void
    {
        $this->password_hash = App::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() : void
    {
        $this->auth_key = App::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() : void
    {
        $this->password_reset_token = App::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() : void
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() : array
    {
        return [
            'email' => Yii::t('users', 'E-mail'),
            'statusName' => Yii::t('users', 'Status'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }
}
