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

//use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\modules\cms\models\CmsRoute */
/* @var $form yii\widgets\ActiveForm */



if (!isset($staticOnly)) $staticOnly = false;

?>

        <? $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 3]]);
        echo $form->errorSummary($model);
        ?>
        
            <?php echo Form::widget([
                'model'=>$model,
                'form'=>$form,
                'staticOnly'=>$staticOnly,
                'columns'=>1,
                'attributes'=>[
                    'container_app_id'=>[
                        'type'=>Form::INPUT_DROPDOWN_LIST,
                        'items'=>\yii\helpers\ArrayHelper::map($apps, 'id', 'name')
                    ],
                ],
            ]); ?>


<?php echo Form::widget([
                'model'=>$model,
                'form'=>$form,
                'staticOnly'=>$staticOnly,
                'columns'=>1,
                'attributes'=>[
                    'nodeType'=>[
                        'type'=>Form::INPUT_DROPDOWN_LIST,
                        'items'=>$model->getTypeDropdownData(),
                        'hint'=>'<b>Документ</b> — создать страницу с произвольным HTML содержимым<br />
                    <b>Контроллер</b> — привязать один из существующих контроллеров<br />
                    <b>Редирект</b> — перенаправить пользователя по ссылке на страницу или файл<br />'
                    ],
                    'nodeBackendName'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Отображается только в админке'],
                    'nodeRoute'=>['type'=>Form::INPUT_TEXT, 'hint'=>'Относительный путь к этой странице, от корня фронтенда.<br /><small>например: <b>contacts.html</b> или  <b>company/news</b></small>'],
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
                            'type'=>Form::INPUT_CHECKBOX,
                        ],
                    ],
                ]); ?>
            </div>
            <div style="margin-left:0px" class="alert alert-info available-for-document  available-for-controller <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
                <p>
                    <?php echo ($model->isNewRecord || !in_array($model->nodeType, array('controller', 'document')))
                        ? 'Вы можете отредактировать содержимое страницы <a href="#" onclick=\'$("#cms-form-tabs a[href=#cms-form-tabs_tab_2]").trigger("click");return false\'>здесь</a>'
                        : 'Редактор текста/HTML будет доступен на следующем шаге'
                    ?>
                </p>
            </div>

            <hr/>
            <!-- Controller Action Selector -->
            <?=$this->render('_controller_config_form', compact('model', 'form', 'staticOnly'))?>

            <div class="clearfix available-for-controller available-for-document <?php if (!in_array($model->nodeType, array('controller', 'document'))) echo 'hidden_el'; ?>">
                <?php echo Form::widget([
                    'model'=>$model,
                    'form'=>$form,
                    'staticOnly'=>$staticOnly,
                    'columns'=>1,
                    'attributes'=>[
                        'viewTemplate'=>[
                            'type'=>Form::INPUT_DROPDOWN_LIST,
                            'items'=>$model->templatesDropdownOptions()
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

            <!-- Redirect URL -->
            <div class="clearfix available-for-forward <?php if ($model->nodeType !== 'forward') echo 'hidden_el'; ?>">
                <?php echo $form->field($model, 'redirect_to')->label('Редирект на ссылку:')->hint('Укажите URL (относительный или абсолютный), на который должен быть перенаправлен посетитель этой страницы'); ?>
            </div>

            <div class="pull-right-">
                <?= Html::button('Submit', ['type'=>'submit', 'class'=>'btn btn-primary btn-lh']) ?>
            </div>

        
        <?  ActiveForm::end(); ?>
