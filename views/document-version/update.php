<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsDocumentVersion */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['pages/index']];
$this->params['breadcrumbs'][] = ['label' => $model->document->name, 'url' => ['pages/view', 'id' => $model->document->id]];
$this->params['breadcrumbs'][] = $model->name;

$this->beginBlock('actions');
echo \yii\helpers\Html::a('Preview <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
    'class' => 'btn btn-primary',
    'target' => '_blank'
]);
$this->endBlock();
?>
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        <div class="panel panel-body">
            <h5 class="text-uppercase">Настройки</h5>
            <?php echo $this->render('_form', [
                'model' => $model,
            ]) ?>
            <hr class="hr" />
            <div class="pull-right">
                <?php echo Html::a('Добавить блок', ['version-content/create', 'containerId' => $model->id], [
                    'class' => 'btn btn-default'])?>
            </div>
            <h5 class="text-uppercase"><?php echo Yii::t('cms', 'Content')?></h5>
            <?php echo \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table--no-sort'],
                'layout' => '{items}',
                'columns' => [
                    'contentBlockName',
                    [
                        'attribute' => 'content',
                        'value' => function($data) {
                            $text = $data->content ? $data->content : $data->content_ru;
                            $text = strip_tags($text);
                            return \yii\helpers\StringHelper::truncate($text, 124);
                        },
                        'format' => 'raw',
                    ],
                    'contentType',
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons'=> [
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post',
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return \yii\helpers\Html::a(' &nbsp; <i class="fa fa-pencil"></i> &nbsp; ', $url, [
                                    'class' => 'btn btn-default btn-sm',
                                    'title' => Yii::t('app', 'Edit'),
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            return \yii\helpers\Url::toRoute(['version-content/' . $action, 'id' => $model->id]); // your own url generation logic
                        }
                    ],
                ],
            ]); ?>



            <hr class="hr" />

            <?php echo Html::a('Удалить', ['document-version/delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-method' => 'post',
                'data-confirm' => 'Вы уверены, что хотите удалить эту версию?'])?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-body">
            <h5 class="text-uppercase">Публикация</h5>

            <?php echo Html::a('Опубликовать', ['document-version/publish', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data-method' => 'post',
                'data-confirm' => 'Вы уверены, что хотите опубликовать эту версию страницы?'])?>
        </div>
    </div>
</div>
