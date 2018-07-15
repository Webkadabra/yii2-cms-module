<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = '//slim';

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
        <?= GridView::widget([
            'bordered' => 0,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'domain',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    </div>
</div>
