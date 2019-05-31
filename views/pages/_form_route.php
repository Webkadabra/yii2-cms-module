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
        'nodeBackendName'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Отображается только в админке'],
        'nodeRoute'=>[
            'type'=>Form::INPUT_TEXT,
            'hint'=>'Относительный путь к этой странице, от корня сайта.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>',
            'fieldConfig' => [
                'addon' => [
                    'groupOptions' => ['class' => 'input-group--seamless'],
                    'prepend' => Html::tag('span', Yii::$app->urlManager->createAbsoluteUrl('/'), ['class' => 'input-group-addon input-group-addon--seamless'])
                ]
            ]
        ],
        'nodeEnabled'=>[
            'type'=>Form::INPUT_WIDGET, 'widgetClass'=>\bookin\aws\checkbox\AwesomeCheckbox::class,
            // hide label column, because it would be duplicate of label inside of a CHECKBOX element. To do so,
            // we must set `label` as empty string, because `false` would break form layout (whole label column
            // will be hidden) and setting it ti NULL would display default label:
            'label' => '',
        ],
    ],
]); ?>
    <div class="clearfix available-for-controller available-for-document <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
        <?php echo Form::widget([
            'model'=>$model,
            'form'=>$form,
            'staticOnly'=>$staticOnly,
            'columns'=>1,
            'attributes'=>[
                'sitemap_yn'=>[
                    'type'=>Form::INPUT_WIDGET, 'widgetClass'=>\bookin\aws\checkbox\AwesomeCheckbox::class,
                    'label' => '',
                ],
            ],
        ]); ?>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <?php echo Html::button('Submit', ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
                </div>
            </div>
        </div>
    </div>


<?php ActiveForm::end(); ?>
