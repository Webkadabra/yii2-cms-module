<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsDocumentVersion */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['pages/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create page');
?>
<div class="page-create">
    
    <h2><?php echo Html::encode($this->title) ?></h2>
    
    <?php echo $this->render('_form', [
        'model' => $model,
        'parent' => $parent,
        'blockOptions' => $blockOptions,
    ]) ?>
    
</div>