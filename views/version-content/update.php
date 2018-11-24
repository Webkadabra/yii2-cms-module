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
$this->params['breadcrumbs'][] = ['label' => $documentVersion->name, 'url' => ['document-version/update', 'id' => $documentVersion->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit «{name}» block', ['name' => $model->name]);

?>
    <div class="row">
        <div class="col-md-3">
            <div class="card card-muted back-to-product">
                <div class="row">
                    <div class="col-xs-12">
                        <h4 class="product-title"><?=Html::encode($documentVersion->document->name)?></h4>
                        <h5><?=$documentVersion->name?></h5>
                        <?=Html::a('<i class="fa fa-chevron-left"></i> Вернуться', ['document-version/update', 'id' => $documentVersion->id])?>
                    </div>
                </div>
            </div>

            <div class="card card-muted back-to-product">
                <?php
                $variants = [];
                foreach ($model->page->contentBlocks as $item) {
                    $variants[] = ['content' => $item->name . '<small class="text-muted pull-right">'.$item->contentType.'</small>', 'url' => \yii\helpers\Url::toRoute(['update', 'id' => $item->id]),
                        'options' => [
                            'data-filter' => 'products',
                            'class' => $item->id == $model->id
                                ? 'active'
                                : '',
                        ],];
                }
                echo \kartik\helpers\Html::listGroup($variants)?>
            </div>

        </div>

        <div class="col-md-8">


            <?php echo $this->render('_form', [
                'model' => $model,
                'documentVersion' => $documentVersion,
            ]) ?>

        </div>
    </div>
    <hr class="hr" />

<?php echo Html::a('Удалить', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-default',
    'data-method' => 'post',
    'data-confirm' => 'Вы уверены, что хотите удалить эту версию?'])?>