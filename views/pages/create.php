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
$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'Pages'), 'url' => ['pages/index']];
$this->params['breadcrumbs'][] = Yii::t('cms', 'Create page');
?>
<div class="panel panel-body">
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
