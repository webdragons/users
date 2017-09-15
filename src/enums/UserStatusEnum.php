<?php
namespace bulldozer\users\enums;

use yii2mod\enum\helpers\BaseEnum;

class UserStatusEnum extends BaseEnum
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @var array
     */
    public static $list = [
        self::STATUS_DELETED => 'Deleted',
        self::STATUS_ACTIVE => 'Active',
    ];

    /**
     * @var string
     */
    public static $messageCategory = 'users';
}