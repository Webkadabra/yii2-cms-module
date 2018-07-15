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
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
$this->context->layout = '//slim';

$this->beginBlock('actions');
echo \yii\helpers\Html::a('View <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
    'class' => 'btn btn-default',
    'target' => '_blank'
]);
$this->endBlock();
?>
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        <div class="card">
            <?php echo $this->render('_form_route', [
                'model' => $model,
                'staticOnly' => $model,
            ]) ?>
            <hr/>
            <?php echo $this->render('_form_properties', [
                'model' => $model,
                'staticOnly' => true,
            ]) ?>
        </div>
    </div>
    <div class="col-md-3">
        <?php if ($model->versions) {
            echo count($model->versions) .' versions:';
            $dataProvider = new \yii\data\ArrayDataProvider();
            $dataProvider->models = $model->versions;
            $dataProvider->pagination = false;
            echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'list-group'],
                'itemView' => function ($version, $key, $index, $widget) use ($model) {
                    /** @var \common\modules\cms\models\CmsDocumentVersion $version */
                    return \yii\helpers\Html::a($version->name, \yii\helpers\Url::toRoute(['/cms/document-version/view',
                        'id' => $version->id]), ['class' => 'list-group-item '
                        . ($version->id == $model->version_id ? 'active' : '')]);
                }
            ]);
        } ?>
        <?php echo \yii\helpers\Html::a(Yii::t('cms', 'Create New Version'), ['/cms/document-version/create','page' => $model->id], [
            'class' => 'btn btn-default'
        ]);?>
    </div>
</div>

