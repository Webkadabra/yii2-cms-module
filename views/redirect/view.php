<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsRoute */

$this->title = Yii::t('cms', 'URL Redirect');
$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'Redirects'), 'url' => ['redirect/index', 'appId' => $model->container_app_id]];
$this->params['breadcrumbs'][] =  Yii::t('cms', 'URL Redirect');

$this->beginBlock('links');
$this->endBlock();
?>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-body">
            <?php echo $this->render('_form_route', [
                'model' => $model,
                'staticOnly' => $model,
            ]) ?>
        </div>
    </div>
    <div class="col-md-4 hidden">

    </div>
</div>


<?php $this->beginBlock('footer')?>
<hr />
<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-9">
            <?=\yii\helpers\Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>
<?php $this->endBlock('footer')?>

