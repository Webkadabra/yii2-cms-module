<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsContentBlock */

$this->title = $containerModel->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];
$this->params['breadcrumbs'][] = ['label' => $containerModel->name, 'url' => ['/cms/pages/view', 'id' => $containerModel->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['/cms/blocks', 'id' => $containerModel->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit «{name}» block', ['name' => $model->name]);
?>
<div class="order-create">
    
    <h2><?php echo Html::encode($this->title) ?></h2>
    
    <?php echo $this->render('_form', [
        'model' => $model,
        'containerModel' => $containerModel,
    ]) ?>
    
</div>
