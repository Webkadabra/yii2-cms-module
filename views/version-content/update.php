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
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];

$this->params['breadcrumbs'][] = ['label' => $documentVersion->document->name, 'url' => ['/cms/pages/view', 'id' => $documentVersion->document->id]];
$this->params['breadcrumbs'][] = ['label' => $documentVersion->name, 'url' => ['/cms/document-version/view', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['/cms/version-content', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit «{name}» block', ['name' => $model->name]);
$this->context->layout = '//slim';
?>
<div class="page-create">

    <h2><?php echo Html::encode($this->title) ?></h2>

    <?php echo $this->render('_form', [
        'model' => $model,
        'documentVersion' => $documentVersion,
    ]) ?>

</div>
    <hr class="hr" />

<?php echo Html::a('Удалить', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-default',
    'data-method' => 'post',
    'data-confirm' => 'Вы уверены, что хотите удалить эту версию?'])?>