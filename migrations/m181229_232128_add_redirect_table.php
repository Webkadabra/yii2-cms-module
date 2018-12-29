<?php

use yii\db\Migration;

/**
 * Class m181229_232128_add_redirect_table
 */
class m181229_232128_add_redirect_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TABLE IF EXISTS `cms_redirect`;
CREATE TABLE `cms_redirect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `container_app_id` int(10) unsigned DEFAULT NULL,
  `redirect_from` varchar(255) NOT NULL,
  `redirect_to` varchar(255) DEFAULT NULL,
  `updated_on` datetime NOT NULL,
  `deleted_yn` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `container_app_id` (`container_app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181229_232128_add_redirect_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181229_232128_add_redirect_table cannot be reverted.\n";

        return false;
    }
    */
}
