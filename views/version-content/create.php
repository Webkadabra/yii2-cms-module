<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\modules\cms\models\CmsDocumentVersionContent */
/* @var $documentVersion \common\modules\cms\models\CmsDocumentVersion */

$this->context->layout = '//slim';

$this->title = $documentVersion->name;

$this->params['breadcrumbs'][] = ['label' => $documentVersion->document->name, 'url' => ['/cms/pages/view', 'id' => $documentVersion->document->id]];
$this->params['breadcrumbs'][] = ['label' => $documentVersion->name, 'url' => ['/cms/document-version/view', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['/cms/version-content', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add block');
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'documentVersion' => $documentVersion,
    ]) ?>

</div>
