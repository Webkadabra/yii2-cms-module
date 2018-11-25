<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\adminControllers;
use webkadabra\yii\modules\cms\models\CmsDocumentVersion;
use webkadabra\yii\modules\cms\models\CmsDocumentVersionContent;
use dektrium\user\filters\AccessRule;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class VersionContentController
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\backendControllers
 */
class VersionContentController extends Controller
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
     * Finds the CmsDocumentVersionContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CmsDocumentVersionContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsDocumentVersionContent::find()->where(['id' => $id])->limit(1)->multilingual()->one()) !== null) {
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
        if (($model = CmsDocumentVersion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex($id)
    {
        $model = $this->findContainerModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => CmsDocumentVersionContent::find()->where(['version_id' => $id]),
            'pagination' => false,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'documentVersion' => $model,
        ]);
    }

    /**
     * Creates a new ShopItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($containerId)
    {
        $documentVersion = $this->findContainerModel($containerId);
        $model = new CmsDocumentVersionContent();
        $model->version_id = $containerId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'documentVersion' => $documentVersion,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $documentVersion = $this->findContainerModel($model->version_id);
        $dataProvider = new ActiveDataProvider([
            'query' => CmsDocumentVersionContent::find()->where(['version_id' => $documentVersion->id]),
            'pagination' => false,
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $id]);
        }
        return $this->render('update', [
            'model' => $model,
            'documentVersion' => $documentVersion,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->addFlash('success', 'Block removed'); // todo: i18n
        return $this->redirect(['document-version/update', 'id' => $model->version_id]);
    }
}