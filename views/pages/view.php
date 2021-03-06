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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['pages/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['pages/view', 'id' => $model->id]];

$this->beginBlock('actions');

echo \yii\helpers\Html::a(Yii::t('cms', 'Set as Home Page'), ['set-homepage', 'id' => $model->id], [
    'class' => 'btn btn-default ' . ($model->getIsHomePage() ? 'btn-disabled' : ''),
    'data' => [
        'confirm' => Yii::t('cms', 'Are you sure you want set this page as a Home Page?'),
        'method' => 'post',
    ],
]);

echo \yii\helpers\Html::a(Yii::t('cms', 'Open') . ' <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
    'class' => 'btn btn-default',
    'target' => '_blank'
]);
$this->endBlock();
?>
<div class="row">
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-body">
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
    </div>
    <div class="col-md-4">
        <?php if ($model->versions) {
            echo '<p>'.count($model->versions) .' version(s):</p>';
            $dataProvider = new \yii\data\ArrayDataProvider();
            $dataProvider->models = $model->versions;
            $dataProvider->pagination = false;
            echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'list-group'],
                'layout' => '{items}',
                'itemView' => function ($version, $key, $index, $widget) use ($model) {
                    /** @var \common\modules\cms\models\CmsDocumentVersion $version */
                    return \yii\helpers\Html::a($version->name, \yii\helpers\Url::toRoute(['document-version/view',
                        'id' => $version->id]), ['class' => 'list-group-item '
                        . ($version->id == $model->version_id ? 'active' : '')]);
                }
            ]);
        } ?>
        <?php echo \yii\helpers\Html::a(Yii::t('cms', 'Create New Version'), ['document-version/create','page' => $model->id], [
            'class' => 'btn btn-default'
        ]);?>
    </div>
</div>


<?php $this->beginBlock('footer')?>
<hr />
<div class="container-fluid">
<div class="row">
    <div class="col-lg-5 col-lg-offset-2 col-md-8">
        <?=\yii\helpers\Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-default',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>
</div>
<?php $this->endBlock('footer')?>

