<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsRoute */
/* @var $form yii\widgets\ActiveForm */

if (!isset($staticOnly)) $staticOnly = false;
?>
<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 3]]); ?>
<?php echo $form->errorSummary($model); ?>
<?php echo Form::widget([
    'model'=>$model,
    'form'=>$form,
    'staticOnly'=>$staticOnly,
    'columns'=>1,
    'attributes'=>[
        'redirect_from'=>[
            'type'=>Form::INPUT_TEXT,
            'hint'=>'Относительный путь к этой странице, от корня сайта.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>',
            'fieldConfig' => [
                'addon' => $model->cmsApp
                    ?
                    [
                        'groupOptions' => ['class' => 'input-group--seamless'],
                        'prepend' => Html::tag('span', $model->cmsApp->base_url, ['class' => 'input-group-addon input-group-addon--seamless'])
                    ]
                    : [],
            ]
        ],
        'redirect_to'=>[
            'type'=>Form::INPUT_TEXT,
            'hint'=>'Укажите URL (относительный или абсолютный), на который должен быть перенаправлен посетитель этой страницы',
//            'fieldConfig' => [
//                'addon' => $model->cmsApp
//                    ?
//                    [
//                        'groupOptions' => ['class' => 'input-group--seamless'],
//                        'prepend' => Html::tag('span', $model->cmsApp->base_url, ['class' => 'input-group-addon input-group-addon--seamless'])
//                    ]
//                    : [],
//            ]
        ],
    ],
]); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <?php echo Html::button(Yii::t('app', 'Save'), ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
                </div>
            </div>
        </div>
    </div>


<?php ActiveForm::end(); ?>
