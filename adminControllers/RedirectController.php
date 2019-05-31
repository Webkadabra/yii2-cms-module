<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\adminControllers;

use webkadabra\yii\modules\cms\models\CmsApp;
use webkadabra\yii\modules\cms\models\CmsRedirect;
use dektrium\user\filters\AccessRule;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PagesController
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\backendControllers
 */
class RedirectController extends Controller
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
     * Finds the CmsRedirect model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CmsRedirect the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsRedirect::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all CmsRedirect models.
     * @return mixed
     */
    public function actionIndex($filter = null)
    {
        $query = CmsRedirect::find();
        $searchModel = new CmsRedirect();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => 'redirect_from ASC'
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new CmsRedirect model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent_id=null)
    {
        if ($parent_id && intval($parent_id)) {
            $parent = CmsRedirect::find()->where(['id' => $parent_id])->limit(1)->one();
            if (!$parent) {
                throw new NotFoundHttpException();
            }
        } else {
            $parent = null;
        }
        $model = new CmsRedirect();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('cms', 'Changes Saved'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'parent' => $parent,
            ]);
        }
    }

    /**
     * Displays a single CmsRedirect model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('cms', 'Changes Saved'));
            return $this->refresh();
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single CmsRedirect model.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->addFlash('warning', Yii::t('cms', 'Page deleted'));
            return $this->redirect(['index']);
        }
        return $this->redirect(['index']);
    }
}
