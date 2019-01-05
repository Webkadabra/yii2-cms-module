<?php
use \webkadabra\yii\modules\cms\components\AdminViewHooks;
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsApp */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'Websites'), 'url' => ['apps/index', 'fromId' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->beginBlock('actions');
echo \yii\helpers\Html::a(Yii::t('cms', 'Open') . ' <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
    'class' => 'btn btn-default',
    'target' => '_blank'
]);
$this->endBlock();

$buttons = [
    \yii\helpers\Html::a(Yii::t('cms', 'Pages') . ' <i class="fa fa-list" aria-hidden="true"></i>',
        ['pages/index', 'appId' => $model->id], [
            'class' => 'btn btn-link',
        ]),
    \yii\helpers\Html::a(Yii::t('cms', 'Redirects') . ' <i class="fa fa-exchange" aria-hidden="true"></i>',
        ['redirect/index', 'appId' => $model->id], [
            'class' => 'btn btn-link',
        ]),
    \yii\helpers\Html::a(Yii::t('cms', 'Open') . ' <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
        'class' => 'btn btn-link',
        'target' => '_blank'
    ]),
];

$event = new \webkadabra\yii\modules\cms\components\events\NavigationLinks(['sender' => $model, 'buttons' => $buttons]);
\yii\base\Event::trigger(AdminViewHooks::class, AdminViewHooks::APP_VIEW_LINKS_BUTTONS, $event);

$this->beginBlock('links');
echo implode('', $event->buttons);
$this->endBlock();

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'staticOnly' => $model,
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-3"><!-- Form Sidebar--></div>
</div>

