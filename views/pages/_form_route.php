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
        'nodeRoute'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Относительный путь к этой странице, от корня фронтенда.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>'],
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
<?php echo Form::widget([
    'model'=>$model,
    'form'=>$form,
    'staticOnly'=>$staticOnly,
    'columns'=>1,
    'attributes'=>[
        'appendTo' => [
            'type'=>Form::INPUT_WIDGET,
            'widgetClass'=>'\kartik\tree\TreeViewInput',
            'options' => [
                'query' => \webkadabra\yii\modules\cms\models\CmsRoute::find()
                    ->andWhere(['container_app_id' => $model->container_app_id])
                    ->addOrderBy('tree_root, tree_level, tree_left'),
                'headingOptions' => ['label' => 'Pages'],
                'rootOptions' => ['label'=>'<i class="fa fa-home text-success"></i>'],
                'fontAwesome' => false,
                'isAdmin' => 0,
                'asDropdown' => true,
                'multiple' => false,
            ]
        ],
        'moveToRoot'=>['type'=>Form::INPUT_WIDGET, 'widgetClass'=>\bookin\aws\checkbox\AwesomeCheckbox::class,
            'visible' => !$model->isRoot(), 'label' => '',
        ],
        'adoptAllPages'=>['type'=>Form::INPUT_WIDGET, 'widgetClass'=>\bookin\aws\checkbox\AwesomeCheckbox::class,
            'visible' => $model->isRoot(), 'label' => '',
        ],
    ],
]); ?>

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