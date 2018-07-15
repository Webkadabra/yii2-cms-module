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

$this->context->layout = '//slim';
$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];
$this->params['breadcrumbs'][] = ['label' => $model->document->name, 'url' => ['/cms/pages/view', 'id' => $model->document->id]];
$this->params['breadcrumbs'][] = $model->name;

$this->beginBlock('actions');
echo \yii\helpers\Html::a('Preview <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
    'class' => 'btn btn-primary',
    'target' => '_blank'
]);
$this->endBlock();
?>
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        <div class="card card-loose">
            <h5 class="text-uppercase">Настройки</h5>
            <?php echo $this->render('_form', [
                'model' => $model,
            ]) ?>
            <hr class="hr" />
<!--        </div>-->
<!---->
<!--        <div class="card ">-->
            <h5 class="text-uppercase">Содержимое</h5>
            <?php echo Html::a('Редактировать содержимое', ['/cms/version-content', 'id' => $model->id], [
    'class' => 'btn btn-default'])?>

            <hr class="hr" />

            <?php echo Html::a('Удалить', ['/cms/document-version/delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
            'data-method' => 'post',
            'data-confirm' => 'Вы уверены, что хотите удалить эту версию?'])?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-loose">
            <h5 class="text-uppercase">Публикация</h5>

            <?php echo Html::a('Опубликовать', ['/cms/document-version/publish', 'id' => $model->id], [
    'class' => 'btn btn-primary',
            'data-method' => 'post',
            'data-confirm' => 'Вы уверены, что хотите опубликовать эту версию страницы?'])?>
        </div>
    </div>
</div>
