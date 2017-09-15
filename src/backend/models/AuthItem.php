<?php

namespace bulldozer\users\backend\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() : string
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() : array
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthAssignments() : ActiveQuery
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRuleName() : ActiveQuery
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren() : ActiveQuery
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren0() : ActiveQuery
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getChildren() : ActiveQuery
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getParents() : ActiveQuery
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() : array
    {
        return [
            'name' => Yii::t('users', 'Name'),
            'codename' => Yii::t('users', 'Code name'),
            'type' => Yii::t('users', 'Type'),
            'description' => Yii::t('users', 'Description'),
            'rule_name' => Yii::t('users', 'Rule name'),
            'data' => Yii::t('users', 'Data'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }
}
