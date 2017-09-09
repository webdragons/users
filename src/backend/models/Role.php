<?php

namespace bulldozer\users\backend\models;

use yii\db\ActiveQuery;
use yii\rbac\Item;

/**
 * Class Role
 * @package bulldozer\users\backend\models
 */
class Role extends AuthItem
{
    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        $query = parent::find()->andWhere(['type' => Item::TYPE_ROLE]);
        return $query;
    }
}