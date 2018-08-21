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
    'formConfig' => ['labelSpan' => 3]]);
?>
<?php echo $form->errorSummary($model); ?>
<?php echo Form::widget([
    'model'=>$model,
    'form'=>$form,
    'staticOnly'=>$staticOnly,
    'columns'=>1,
    'attributes'=>[
        'name'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Отображается только в админке'],
        'code'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Относительный путь к этой странице, от корня фронтенда.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>'],
        'domain'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Относительный путь к этой странице, от корня фронтенда.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>'],
        'base_url'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Относительный путь к этой странице, от корня фронтенда.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>'],
        'url_component'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Относительный путь к этой странице, от корня фронтенда.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>'],
    ],
]); ?>
<?php echo Form::widget([
    'model'=>$model,
    'form'=>$form,
    'staticOnly'=>$staticOnly,
    'columns'=>1,
    'attributes'=>[
        'active_yn'=>[
            'type'=>Form::INPUT_WIDGET, 'widgetClass'=>\bookin\aws\checkbox\AwesomeCheckbox::class,
        ],
    ],
]); ?>
<div class="pull-right-">
    <?= Html::button('Submit', ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
</div>
<?  ActiveForm::end(); ?>