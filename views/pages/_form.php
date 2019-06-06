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
echo $form->errorSummary($model);
?>

<script>
    function slugifyString ( str ) {
        var chrmap = {
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
            'е': 'e', 'ё': 'e', 'ж': 'j', 'з': 'z', 'и': 'i',
            'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
            'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
            'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh',
            'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'u', 'я': 'ya'
        }, n_str = [];

        str = str.replace(/[ъь]+/g, '').replace(/й/g, 'i');
        str = str.replace(/[\s-]+/g, '_');
        str = str.replace(/_+/g, '_')
            .replace(/_$/g, '');

        for ( var i = 0; i < str.length; ++i ) {
            n_str.push(
                chrmap[ str[i] ]
                || chrmap[ str[i].toLowerCase() ] == undefined && str[i]
                || chrmap[ str[i].toLowerCase() ].replace(/^(.)/, function ( match ) { return match.toUpperCase() })
            );
        }

        str =  n_str.join('');
        str = str.toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-').trim('_');

        return str;
    }

    function onPermalinkGenerateClick() {
        var title = document.getElementById('<?=Html::getInputId($model,'nodeBackendName')?>').value,
            generatedPermalink;
        if (!title) {
            generatedPermalink = generateId(12) + '.html'
        } else {
            if (title.length < 5) {
                generatedPermalink = slugifyString(title) + '_' + generateId(5);
            } else {
                generatedPermalink = slugifyString(title);
            }
        }
        document.getElementById('<?=Html::getInputId($model,'nodeRoute')?>').value = generatedPermalink;
    }

    // dec2hex :: Integer -> String
    function dec2hex (dec) {
        return ('0' + dec.toString(16)).substr(-2)
    }

    // generateId :: Integer -> String
    function generateId (len) {
        var arr = new Uint8Array((len || 40) / 2)
        window.crypto.getRandomValues(arr)
        return Array.from(arr, dec2hex).join('')
    }
</script>

<?php echo Form::widget([
    'model'=>$model,
    'form'=>$form,
    'staticOnly'=>$staticOnly,
    'columns'=>1,
    'attributes'=>[
        'nodeType'=>[
            'type'=>Form::INPUT_DROPDOWN_LIST,
            'items'=>$model->getTypeDropdownData(),
        ],
        'nodeBackendName'=>['type'=>Form::INPUT_TEXT,],
        'nodeRoute'=>[
            'type'=>Form::INPUT_TEXT,
            'hint'=>'Относительный путь к этой странице, от корня сайта. '
                . Html::button(Yii::t('cms', 'Generate'), ['onclick' => 'onPermalinkGenerateClick();return false;', 'class' => 'btn btn-xs btn-default'])
                .'<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>',
            'fieldConfig' => [
                'addon' => [
                    'groupOptions' => ['class' => 'input-group--seamless'],
                    'prepend' => Html::tag('span', Yii::$app->urlManager->createAbsoluteUrl('/'), ['class' => 'input-group-addon input-group-addon--seamless'])
                ]
            ]
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
            ],
        ],
    ]); ?>
</div>

<hr/>
<!-- Controller Action Selector -->
<?php echo $this->render('_controller_config_form', compact('model', 'form', 'staticOnly'))?>

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
        ],
    ]); ?>
</div>

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

<div class="col-md-offset-3">
    <?php echo Html::button('Submit', ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
</div>


<?php ActiveForm::end(); ?>

