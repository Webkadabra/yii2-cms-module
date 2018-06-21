<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\adminControllers;

use webkadabra\yii\modules\cms\models\CmsContentBlock;
use webkadabra\yii\modules\cms\models\CmsDocumentVersion;
use webkadabra\yii\modules\cms\models\CmsDocumentVersionContent;
use webkadabra\yii\modules\cms\models\CmsNodeVersion;
use webkadabra\yii\modules\cms\models\CmsRoute;
use dektrium\user\filters\AccessRule;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class DocumentVersionController
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package common\modules\cms\backendControllers
 */
class DocumentVersionController extends Controller
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
        if (class_exists('AccessRule')) {
            $rules['access']['ruleConfig'] = [
                'class' => AccessRule::className(),
            ];
        }
        return $rules;
    }

    /**
     * Finds the CmsDocumentVersion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CmsDocumentVersion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsDocumentVersion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all CmsDocumentVersion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CmsDocumentVersion::find(),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CmsDocumentVersion model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Creates a new CmsDocumentVersion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($page)
    {
        $parent = CmsRoute::find()->where(['id' => $page])->limit(1)->one();
        if (!$parent) {
            throw new NotFoundHttpException();
        }
        $model = new CmsDocumentVersion();
        foreach (['nodeType', 'nodeProperties', 'viewTemplate', 'viewLayout'] as $attribute) {
            $model->{$attribute} = $parent->{$attribute};
        }
//        if (Yii::$app->request->isPost) {
//            $className = explode('\\', ($model->className()));
//            $className = array_pop($className);
//            $bodyParams = Yii::$app->request->bodyParams;
//            if (isset($_POST[$className]['category_ids'])) {
//                if (empty($bodyParams[$className]['category_ids'])) {
//                    $bodyParams[$className]['category_ids'] = [];
//                } else {
//                    $bodyParams[$className]['category_ids'] = explode(',', $_POST[$className]['category_ids']);
//                }
//                Yii::$app->request->setBodyParams($bodyParams);
//            }
//        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->copyPageBlockIds) {
                if (!$parent->version_id) {
                    foreach ($model->copyPageBlockIds as $copyPageBlockId) {
                        $block = CmsContentBlock::findOne($copyPageBlockId);
                        if ($block) {
                            $copy = new CmsDocumentVersionContent();
                            $copy->version_id = $model->id;
                            $copy->content = $block->content;
                            $copy->contentBlockName = $block->contentBlockName;
                            $copy->contentType = $block->contentType;
                            $copy->sort_order = $block->sort_order;
                            $copy->save(false);
                        }
                    }
                } else {
                    foreach ($model->copyPageBlockIds as $copyPageBlockId) {
                        $block = CmsDocumentVersionContent::findOne($copyPageBlockId);
                        if ($block) {
                            $copy = new CmsDocumentVersionContent();
                            $copy->version_id = $model->id;
                            $copy->content = $block->content;
                            $copy->contentBlockName = $block->contentBlockName;
                            $copy->contentType = $block->contentType;
                            $copy->sort_order = $block->sort_order;
                            $copy->save(false);
                        }
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            // list of blocks to copy
            if (!$parent->version_id) {
                $blockOptions = ArrayHelper::map($parent->contentBlocks, 'id', 'name');
            } else {
                $blockOptions = ArrayHelper::map($parent->publishedVersion->contentBlocks, 'id', 'name');
            }

            return $this->render('create', [
                'model' => $model,
                'parent' => $parent,
                'blockOptions' => $blockOptions,
            ]);
        }
    }

    /**
     * Updates an existing CmsDocumentVersion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $className = explode('\\', ($model->className()));
            $className = array_pop($className);
            $bodyParams = Yii::$app->request->bodyParams;
            if (isset($_POST[$className]['category_ids'])) {
                if (empty($bodyParams[$className]['category_ids'])) {
                    $bodyParams[$className]['category_ids'] = [];
                } else {
                    $bodyParams[$className]['category_ids'] = explode(',', $_POST[$className]['category_ids']);
                }
                Yii::$app->request->setBodyParams($bodyParams);
            }
        }
//        $models = $model->getItemOptionsDataProvider()->models;
//        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
//            $count = 0;
//            foreach ($models as $index => $modelIN) {
//                if ($modelIN->save()) {
//                    $count++;
//                }
//            }
//        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        if ($model->published_yn || $model->id == $model->document->version_id) {
            throw new Exception('Can not delete published version');
        }
        $model->delete();
        return $this->redirect(['/cms/pages/view', 'id' => $model->node_id]);
    }

    public function actionPublish($id) {
        /** @var CmsDocumentVersion $model */
        $model = $this->findModel($id);
        $model->document->version_id = $model->id;
        $model->document->nodeProperties = $model->nodeProperties;
        $model->document->nodeType = $model->nodeType;
        if ($model->document->save()) {
            $model->published_on = new Expression('NOW()');
            $model->published_yn = 1;
            $model->save(false);
        }
        return $this->redirect(['/cms/pages/view', 'id' => $model->node_id]);
    }
}