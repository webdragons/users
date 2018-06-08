<?php

namespace bulldozer\users\rbac;

use yii\base\BaseObject;

/**
 * Class Item
 * @package bulldozer\users\rbac
 */
class Item extends BaseObject
{
    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;

    /**
     * @var int the type of the item. This should be either [[TYPE_ROLE]] or [[TYPE_PERMISSION]].
     */
    public $type;

    /**
     * @var string the name of the item. This must be globally unique.
     */
    public $name;

    /**
     * @var string
     */
    public $codename;

    /**
     * @var string the item description
     */
    public $description;

    /**
     * @var string name of the rule associated with this item
     */
    public $ruleName;

    /**
     * @var mixed the additional data associated with this item
     */
    public $data;

    /**
     * @var int UNIX timestamp representing the item creation time
     */
    public $createdAt;

    /**
     * @var int UNIX timestamp representing the item updating time
     */
    public $updatedAt;
}
