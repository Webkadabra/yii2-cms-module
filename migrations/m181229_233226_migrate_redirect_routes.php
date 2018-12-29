<?php

use yii\db\Migration;

/**
 * Class m181229_233226_migrate_redirect_routes
 */
class m181229_233226_migrate_redirect_routes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $data = \webkadabra\yii\modules\cms\models\CmsRoute::find()->where(['nodeType' => \webkadabra\yii\modules\cms\models\CmsRoute::TYPE_REDIRECT])->all();
        foreach ($data as $oldRedirect) {
            $redirect = new \webkadabra\yii\modules\cms\models\CmsRedirect();
            $redirect->redirect_from = $oldRedirect->nodeRoute;
            $redirect->redirect_to = $oldRedirect->getRedirect_to();
            $redirect->container_app_id = $oldRedirect->container_app_id;
            if ($oldRedirect->delete()) {
                echo "redirect " . $redirect->redirect_from . " migrated\n";
                $redirect->save(false);
            } else
                echo "redirect " . $redirect->redirect_from . " FAILED to migrate\n";

        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181229_233226_migrate_redirect_routes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181229_233226_migrate_redirect_routes cannot be reverted.\n";

        return false;
    }
    */
}
