<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsRoute */

$this->title = Yii::t('cms', 'Create page');
$this->params['breadcrumbs'][] = ['label' => $appModel->name, 'url' => ['/cms/pages', 'appId' => $appModel->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create page');
?>
<div class="panel panel-body">
    <?php echo $this->render('_form', [
        'model' => $model,
        'apps' => $apps,
    ]) ?>
</div>