<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsDocumentVersionContent */
/* @var $documentVersion webkadabra\yii\modules\cms\models\CmsDocumentVersion */

$this->title = $documentVersion->name;

$this->params['breadcrumbs'][] = ['label' => $documentVersion->document->name, 'url' => ['pages/view', 'id' => $documentVersion->document->id]];
$this->params['breadcrumbs'][] = ['label' => $documentVersion->name, 'url' => ['document-version/view', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['version-content/index', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add block');
?>
<div class="page-create">

    <h2><?php echo Html::encode($this->title) ?></h2>

    <?php echo $this->render('_form', [
        'model' => $model,
        'documentVersion' => $documentVersion,
    ]) ?>

</div>
