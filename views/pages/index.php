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

$this->title = Yii::t('cms', 'Pages');


$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'Websites'),
    'url' => ['apps/index', 'fromId' => $activeApp->id]];
$this->params['breadcrumbs'][] = $this->title;




$this->beginBlock('actions');
echo Html::a(Yii::t('app', 'Create New Page'), [
    'create',
    'appId' => Yii::$app->request->get('appId')
], ['class' => 'btn btn-primary']);
$this->endBlock();
$tabs[] = [
    'encode' => false,
    'label' => '<i class="fa fa-cog"></i> &nbsp; Настройки сайта',
    'url' => $activeApp
        ? ['apps/view', 'id' => $activeApp->id]
        : ['apps/index']
    ,
    'headerOptions'=>['class' => 'pull-right', 'style' => 'position: absolute; right: 40px'],
    'linkOptions'=>['style' => 'border: 0',],
    'class' => 'btn btn-default pull-right',
    'contentOptions'=>['class' => 'btn btn-default pull-right'],
]
?>

<div class="ui-card">
    <div class="ui-card-tabs">
        <?php echo \yii\bootstrap\Tabs::widget([
            'options' => ['class' => 'tabs'],
            'items' => $tabs,
//            'items' => \common\models\SavedObjectFilter::makeTabsConfig($searchModel, $tabs),
        ]); ?>
    </div>
    <?php echo \webkadabra\yii\modules\cms\components\TableFilterWidget::widget([
    ]); ?>
    <form id="megaSearch" class="form-horizontal" method="get" style="padding:25px 20px 0 20px;text-align: center;margin:0;overflow:hidden">
        <input type="text" id="megaSearch-query" class="form-control" name="megaSearch[query]" placeholder="<?=Yii::t('app', 'Search')?>">
    </form>

    <?php Pjax::begin(); ?>
    <div class="card-section card-section--roots">
        <div class="well" style="display: none" id="cmsTable_emptyContent">Ничего не найдено</div>
        <?php echo \yii\grid\GridView::widget([
            'id'=>'cmsTable',
            'layout' => '{items}{pager}{summary}',
            'tableOptions' => [
                'width' => '100%',
                'class' => 'table table-striped',
            ],
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'nodeBackendName',
                    'value' => function($model) {
                        return Html::tag('span', $model->name, ['data-searchable-value' => $model->name]);
                    },
                    'format' => 'raw',
                ],
                [
                    'format' => 'raw',
                    'value' => function($model /** @var \webkadabra\yii\modules\cms\models\CmsRoute $model */) {
                        if (!$model->sitemap_yn && !$model->getIsHomePage()) {
                            return '<span class="fa-stack fa-1x text-muted" title="Not in sitemap">
  <i class="fa  fa-ban fa-stack-2x"></i>
  <strong class="fa-stack-1x fa fa-sitemap"></strong>
</span>';
                        } else {
                            return '';
                        }
                    },
                    'options' => [
                        'width' => '1%'
                    ]
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
                [
                    'format' => 'datetime',
                    'attribute' => 'nodeLastEdit',
                    'contentOptions' => [
                        'width' => '4%',
                        'nowrap' => 'nowrap',
                        'class' => 'nowrap',
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
        <br />
    </div>
</div>
