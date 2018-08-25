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
/* @var $parent webkadabra\yii\modules\cms\models\CmsRoute */
/* @var $model webkadabra\yii\modules\cms\models\CmsDocumentVersion */
/* @var $form yii\widgets\ActiveForm */

if (!isset($staticOnly)) $staticOnly = false;

$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 3]]);
echo $form->errorSummary($model);

if ($model->isNewRecord) {
    echo Html::activeHiddenInput($model,'node_id',['value' => $parent->id]);
} ?>
<style>
    .controller_action_params_container {
        background: #e8e8e8;
        border-left: 3px solid #c5c5c5;
        padding: 6px 6px 7px 14px;
    }
    .hidden_el {
        display: none
    }
</style>
<div class="order-form">
    <?php echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'nodeType'=>[
                'type'=>Form::INPUT_DROPDOWN_LIST,
                'items'=>\webkadabra\yii\modules\cms\models\CmsRoute::typeDropDownData(),
                'hint'=>'<b>Документ</b> — создать страницу с произвольным HTML содержимым<br />
                    <b>Контроллер</b> — привязать один из существующих контроллеров<br />
                    <b>Редирект</b> — перенаправить пользователя по ссылке на страницу или файл<br />'
            ],
            'description'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Отображается только в админке'],
        ],
    ]); ?>
    <div class="clearfix available-for-controller available-for-document <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
        <?php echo Form::widget([
            'model'=>$model,
            'form'=>$form,
            'staticOnly'=>$staticOnly,
            'columns'=>1,
            'attributes'=>[
                'viewTemplate'=>[
                    'type'=>Form::INPUT_DROPDOWN_LIST,
                    'items'=>\webkadabra\yii\modules\cms\models\CmsRoute::templatesDropdownOptions(),
                    'options' => ['empty'=>'', 'prompt' => ''],
                ],
                'viewLayout'=>[
                    'type'=>Form::INPUT_TEXT,
                    'options' => ['hint' => 'Укажите имя файла `layout` без расширения (напр.: `main`)'],
                ],
                'copyPageBlockIds'=>[
                    'type'=>Form::INPUT_WIDGET, 'widgetClass'=>\bookin\aws\checkbox\AwesomeCheckbox::class,
                    'options' => ['list'=>(isset($blockOptions) ? $blockOptions : [])],
                    'visible' => $blockOptions && $model->isNewRecord,
                    'label'=>$model->isNewRecord ? $model->getAttributeLabel('copyPageBlockIds') : false,
                ],
            ],
        ]); ?>
    </div>
    <hr class="hr--stretch"/>
    <!-- Controller Action Selector -->
    <?php echo $this->render('/pages/_controller_config_form', compact('model', 'form', 'staticOnly'))?>

    <!-- Page Title -->
    <div class="clearfix available-for-controller available-for-document <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
        <?php echo $form->field($model, 'page_title')->hint('Заголовок окна страницы (через тег HEAD>TITLE)'); ?>
    </div>

    <!-- META Keywords -->
    <div class="clearfix available-for-controller available-for-document <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
        <?php echo $form->field($model, 'meta_keywords')->hint('META Keywords:'); ?>
    </div>

    <!-- META Description -->
    <div class="clearfix available-for-controller available-for-document <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
        <?php echo $form->field($model, 'meta_description')->hint('META Description:'); ?>
    </div>

    <!-- Redirect URL -->
    <div class="clearfix available-for-forward <?php if ($model->nodeType !== 'forward') echo 'hidden_el'; ?>">
        <?php echo $form->field($model, 'redirect_to')->label('Редирект на ссылку:')->hint('Укажите URL (относительный или абсолютный), на который должен быть перенаправлен посетитель этой страницы'); ?>
    </div>

    <div class="text-center">
        <?php echo Html::button('Submit', ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
    </div>

</div>
<?php ActiveForm::end(); ?>

