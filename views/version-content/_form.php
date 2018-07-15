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
/* @var $model webkadabra\yii\modules\cms\models\CmsDocumentVersionContent */
/* @var $documentVersion webkadabra\yii\modules\cms\models\CmsDocumentVersion */

if (!isset($staticOnly)) $staticOnly = false;
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
echo $form->errorSummary($model);
?>
<div class="page-form">
    <?php
    if ($documentVersion) {
        echo Html::activeHiddenInput($model,'version_id',['value' => $documentVersion->id]);
    }
    $cc = [
        'contentBlockName'=>['type'=>Form::INPUT_DROPDOWN_LIST, 'items'=>$model->blockIdDropdownOptions(), 'hint'=>''],
        'sort_order'=>['type'=>Form::INPUT_TEXT, 'hint'=>''],
        'contentType'=>['type'=>Form::INPUT_DROPDOWN_LIST, 'items'=>\webkadabra\yii\modules\cms\models\CmsContentBlock::typeDropdownOptions(), 'hint'=>''],
        'content' => [
            'type'=>Form::INPUT_WIDGET,
            'widgetClass'=>'\conquer\codemirror\CodemirrorWidget',
            'options' => [
                'preset'=>'html',
                'presetsDir'=>\Yii::getAlias('@common/config/codemirror'),
                'options'=>['rows' => 30,],
            ]
        ],
    ];
    foreach ($model->languages as $lang) {
        if ($model->defaultLanguage != $lang)
            $cc['content_'.$lang] = [
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\conquer\codemirror\CodemirrorWidget',
                'options' => [
                    'preset'=>'html',
                    'presetsDir'=>\Yii::getAlias('@common/config/codemirror'),
                    'options'=>['rows' => 30,],
                ]
            ];
    }
    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>$cc,
    ]);

?>
    <div class="pull-right-">
        <?= Html::button('Submit', ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
    </div>

</div>
<?php ActiveForm::end(); ?>

