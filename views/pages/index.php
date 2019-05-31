<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

use webkadabra\yii\modules\cms\components\AdminViewHooks;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Pages');

$this->params['breadcrumbs'][] = $this->title;

$this->beginBlock('actions');
echo Html::a(Yii::t('cms', 'Create New Page'), [
    'create',
    'appId' => Yii::$app->request->get('appId')
], ['class' => 'btn btn-primary']);
$this->endBlock();

$buttons = [
    \yii\helpers\Html::a(Yii::t('cms', 'Pages') . ' <i class="fa fa-list" aria-hidden="true"></i>',
        ['pages/index'], [
            'class' => 'btn btn-link',
        ]),
    \yii\helpers\Html::a(Yii::t('cms', 'Redirects') . ' <i class="fa fa-exchange" aria-hidden="true"></i>',
        ['redirect/index'], [
            'class' => 'btn btn-link',
        ]),
];

$event = new \webkadabra\yii\modules\cms\components\events\NavigationLinks(['sender' => $this->context, 'buttons' => $buttons]);
\yii\base\Event::trigger(AdminViewHooks::class, AdminViewHooks::PAGES_VIEW_LINKS_BUTTONS, $event);

$this->beginBlock('links');
echo implode('', $event->buttons);
$this->endBlock();


?>

<div class="ui-card">
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
