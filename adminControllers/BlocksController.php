<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\adminControllers;

use webkadabra\yii\modules\cms\models\CmsContentBlock;
use webkadabra\yii\modules\cms\models\CmsRoute;
use yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;

/**
 * Class BlocksController
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\backendControllers
 */
class BlocksController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $rules = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->allowedRoles,
                    ],
                ],
            ],
        ];
        if (class_exists('dektrium\user\filters\AccessRule')) {
            $rules['access']['ruleConfig'] = [
                'class' => \dektrium\user\filters\AccessRule::class,
            ];
        }
        return $rules;
    }
    
    /**
     * Finds the CmsContentBlock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CmsContentBlock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsContentBlock::find()->where(['contentID' => $id])->limit(1)->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the ShopItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findContainerModel($id)
    {
        if (($model = CmsRoute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex($id)
    {
        $model = $this->findContainerModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => CmsContentBlock::find()->where(['Pages_pageID' => $id]),
            'pagination' => false,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'containerModel' => $model,
        ]);
    }

    /**
     * Creates a new ShopItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($containerId)
    {
        $containerModel = $this->findContainerModel($containerId);
        $model = new CmsContentBlock();
        $model->Pages_pageID = $containerId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'containerModel' => $containerModel,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $containerModel = $this->findContainerModel($model->Pages_pageID);
        $dataProvider = new ActiveDataProvider([
            'query' => CmsContentBlock::find()->where(['Pages_pageID' => $containerModel->id]),
            'pagination' => false,
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $id]);
        }
        return $this->render('update', [
            'model' => $model,
            'containerModel' => $containerModel,
        ]);
    }
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $redirect = ['/cms/blocks', 'id' => $model->Pages_pageID];
        $model->delete();
        $containerModel = $this->findContainerModel($model->Pages_pageID);
        $dataProvider = new ActiveDataProvider([
            'query' => CmsContentBlock::find()->where(['Pages_pageID' => $containerModel->id]),
            'pagination' => false,
        ]);
        return $this->renderPartial('index', [
            'dataProvider' => $dataProvider,
            'containerModel' => $containerModel,
        ]);
    }
}