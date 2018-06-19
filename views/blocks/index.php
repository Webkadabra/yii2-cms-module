<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $containerModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];
$this->params['breadcrumbs'][] = ['label' => $containerModel->name, 'url' => ['/cms/pages/update', 'id' => $containerModel->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Content');
$this->context->layout = '//slim';
?>
<div class="order-index">

    <div class="pull-right">
        <?= Html::a(Yii::t('app', 'Add Item'), ['create', 'containerId' => $containerModel->id], ['class' => 'btn btn-primary']) ?>
    </div>

    <h2><?= Html::encode($this->title) ?></h2>


    <?php Pjax::begin(); ?>
    <?php \yii\widgets\ActiveForm::begin(['options'=>['data-pjax'=>true]]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
////            ['class' => 'yii\grid\SerialColumn'],
//            [
//                'format' => 'raw',
//                'value' => function($data) {
//                    return Html::img($data->photo_cart_item,['style'=>'
//                        max-width: 80px;
//                        max-height: 80px;
//                        display: block;
//                        margin:0 auto
//                    ']);
//                },
//            ],
            'contentBlockName',
            [
                'attribute' => 'content',
                'value' => function($data) {
                    $text = $data->content ? $data->content : $data->content_ru;
                    $text = strip_tags($text);
                    return \yii\helpers\StringHelper::truncate($text, 500);
                },
                'format' => 'raw',
            ],
            'contentType',
            'sort_order',
//            [
//                'attribute' => 'available_yn',
//                'format' => 'boolean',
//            ],
            ['class' => 'yii\grid\ActionColumn',
            'buttons'=> [
                'delete' => function ($url, $model, $key)
                {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                    ]);
                },
            ]],
        ],
    ]); ?>
    <?php \yii\widgets\ActiveForm::end(); ?>
    <?php Pjax::end(); ?></div>