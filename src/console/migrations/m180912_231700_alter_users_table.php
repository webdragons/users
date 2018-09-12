<?php

namespace bulldozer\users\console\migrations;

use bulldozer\App;
use bulldozer\users\rbac\DbManager;
use yii\base\InvalidConfigException;
use yii\db\Migration;

class m180912_231700_alter_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%users}}', 'access_token', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%users}}', 'access_token');
    }
}
