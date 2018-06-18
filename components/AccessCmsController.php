<?php

namespace webkadabra\yii\modules\cms\components;

use webkadabra\yii\modules\cms\models\CmsRoute;
use application\components\Controller;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AccessCmsController
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\components
 */
class AccessCmsController extends Controller
{
    /** @var CmsRoute */
    public $node;
    public $contentBlocks;

    /**
     * Display a document page
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     * @todo move to Action class file in CMS module folder
     */
    public function actionPage($id=null) {
        if (isset(Yii::$app->params['view_preview_node']) && Yii::$app->params['view_preview_node']) {
            $this->node = Yii::$app->params['view_preview_node'];
        } elseif (isset(Yii::$app->params['view_node']) && Yii::$app->params['view_node']) {
            $this->node = Yii::$app->params['view_node'];
        } else {
            $this->node = $this->findModel($id);
        }
        if (!$this->node->nodeEnabled) {
            if (!Yii::$app->user->can('previewCms')) {
                throw new NotFoundHttpException();
            } else if (YII_ENV == 'prod') {
                Yii::$app->session->addFlash('warning', 'Only you and other admins can see this page - it is not yet open to the public');
            }
        }
        // make sure user does not try to access a page directly if page's got permalink
        if ($this->node->getRoute() && (trim(Yii::$app->request->pathInfo, '/') != trim($this->node->getRoute(), '/'))) {
            throw new NotFoundHttpException();
        }
        if ($this->node->viewLayout) {
            $this->layout = $this->node->getSafeViewLayout();
        }
        return $this->render($this->node->getViewTemplate(), [
            'model' => $this->node,
        ]);
    }

    /**
     * Finds the ShopItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CmsRoute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsRoute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}