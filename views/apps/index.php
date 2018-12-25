<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Websites');

$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'Content'), 
    'url' => Yii::$app->request->get('fromId') 
    ? ['pages/index', 'appId' => Yii::$app->request->get('fromId')]
    : ['pages/index']

];
$this->params['breadcrumbs'][] = $this->title;

$this->beginBlock('actions');
echo Html::a(Yii::t('cms', 'Add another site'), [
    'create',
    'appId' => Yii::$app->request->get('appId')
], ['class' => 'btn btn-primary']);
$this->endBlock();
?>

<div class="ui-card">
    <div class="ui-card-tabs">
        <?php echo \yii\bootstrap\Tabs::widget([
            'options' => ['class' => 'tabs'],
            'items' => [],
//            'items' => \common\models\SavedObjectFilter::makeTabsConfig($searchModel, $tabs),
        ]); ?>
    </div>
    <?php Pjax::begin(); ?>
    <div class="card-section card-section--roots">
        <?= \yii\grid\GridView::widget([
            'tableOptions' => [
                'width' => '100%',
                'class' => 'table table-striped',
            ],
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'domain',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="fa fa-chevron-right"></span>', $url, [
                            'title' => Yii::t('app', 'Settings'),
                            'class' => 'btn btn-sm btn-default',
                            'data-pjax' => '0',
                        ]);
                    }
                ],
                'options' => [
                    'width' => '1%'
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    </div>
</div>
