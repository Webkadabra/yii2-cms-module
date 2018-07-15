<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsRoute */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/cms/pages/view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['/cms/blocks', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
$this->context->layout = '//slim';
?>
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        <div class="card">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>

