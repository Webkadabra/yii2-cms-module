<?php

use yii\db\Migration;

/**
 * Class m181225_215842_remove_nested_sets_support
 */
class m181225_215842_remove_nested_sets_support extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(\webkadabra\yii\modules\cms\models\CmsRoute::tableName(), 'tree_root');
        $this->dropColumn(\webkadabra\yii\modules\cms\models\CmsRoute::tableName(), 'tree_left');
        $this->dropColumn(\webkadabra\yii\modules\cms\models\CmsRoute::tableName(), 'tree_right');
        $this->dropColumn(\webkadabra\yii\modules\cms\models\CmsRoute::tableName(), 'tree_level');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181225_215842_remove_nested_sets_support cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181225_215842_remove_nested_sets_support cannot be reverted.\n";

        return false;
    }
    */
}
