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

$this->title = Yii::t('cms', 'Create redirect');
$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'Redirects'), 'url' => ['redirect/index', 'appId' => $model->container_app_id]];
$this->params['breadcrumbs'][] = Yii::t('cms', 'Create redirect');
?>
<div class="panel panel-body">
    <?php echo $this->render('_form_route', [
        'model' => $model,
        'apps' => $apps,
    ]) ?>
</div>