<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use kartik\builder\Form;
use yii\helpers\Html;

?>
<?php $currentActionKey = $model->getController_route() ? md5($model->getController_route()) : false; ?>
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
<script>
    var CmsRouterEntity_type, CmsRouterEntity_controller_route, controller_rows, document_rows, submitButton, actionParametersContainer,
        lastActionKey = "<?php echo $currentActionKey;?>", actionKeyMap,
        availableTypes = {
            1:'controller',
            2:'document',
        };

    /**
     * Toggle type-specific form fields
     * @param type_id
     */
    function filterFormFields(type) {
        controller_rows.addClass('hidden_el');
        document_rows.addClass('hidden_el');

        var show = eval(type + '_rows');
        show.removeClass('hidden_el');
        submitButton.attr('disabled', false);

    }

    function toggleActionOptions(action) {
        if (lastActionKey) {
            $('#' + lastActionKey + '_parameters').addClass('hidden_el');
        }

        if (action == '' || !action) {
            actionParametersContainer.addClass('hidden_el');
        } else {
            var actionKey = actionKeyMap[action];
            if (actionKey) {
                // Show this action params and re-set lastActionKey
                $('#' + actionKey + '_parameters').removeClass('hidden_el');
                lastActionKey = actionKey;

                actionParametersContainer.removeClass('hidden_el');
            }
        }
    }

    function initiateCmsForm() {
        CmsRouterEntity_type = $('#<?=Html::getInputId($model,'nodeType')?>'),
            CmsRouterEntity_controller_route = $('#<?=Html::getInputId($model,'controller_route')?>'),

            actionParametersContainer = $('#controller_action_params'),
            submitButton = $('#submitButton'),
            controller_rows = $('div.available-for-controller'),
            document_rows = $('div.available-for-document'),

        CmsRouterEntity_type.on('change',function (e) {
            filterFormFields(this.value);
        });

        CmsRouterEntity_controller_route.change(function (e) {
            toggleActionOptions(this.value);
        });
    }
</script>
<?php $this->registerJs('$(document).ready(function () {
        initiateCmsForm();
    });');?>
<?php
$controllerRouteOptions = $model->getControllerRouteOptions();
// controllerActions
// actionParameters
?>
<div class="available-for-controller <?php if ($model->nodeType !== 'controller') echo 'hidden_el'; ?>">
    <?php echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'controller_route'=>[
                'type'=>Form::INPUT_DROPDOWN_LIST,
                'items'=>\yii\helpers\ArrayHelper::map($controllerRouteOptions['controllerActions'], 'value', 'label', 'group')
            ],
        ],
    ]); ?>
    <div class="form-group ">
        <div class="col-md-offset-3 col-md-9">
            <!-- Action's extended parameters -->
            <?php // @todo It would be easier to load form fields by ajax as more options can become available ?>
            <div class="<?php if (!$model->getAction_parameters()) echo 'hidden_el'; ?> controller_action_params_container"
                 id="controller_action_params">
                <span style="font-weight:bold">Настройки действия: </span> <br/>
                <?php
                $actionKeyMap = array(); // We need to remap strings like "controller/action" to md5 key, since jQuery can't get elements by selector with slash

                foreach ($controllerRouteOptions['actionParameters'] as $actionKey => $actionParameters) {
                    $md5key = md5($actionKey);
                    $actionKeyMap[$actionKey] = $md5key;
                    echo '<div id="' . $md5key . '_parameters" class="' . ($currentActionKey == $md5key ? '' : 'hidden_el') . '">';
                    foreach ($actionParameters as $parameter) {
                        if (is_array($parameter)) {

                            $value = key($parameter);
                            $label = $parameter[$value];
                        } else {
                            $value = $label = $parameter;
                        }
                        echo ucfirst($label) . ': '
                            . Html::textInput($md5key . '_' . $value . '_param',
                                (isset($model->action_parameters[$value]) ? $model->action_parameters[$value] : '')/*, array('id'=>'')*/) . '<br />';
                    }
                    echo '</div>';
                }
                ?>
                <script>
                    actionKeyMap = <?php echo \yii\helpers\Json::encode($actionKeyMap); ?>;
                </script>
            </div>
        </div>
        <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
    </div>
</div>
