<?php
namespace webkadabra\yii\modules\cms;

use yii;

/**
 * Class CmsPageFormTrait
 * @author sergii gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms
 */
trait CmsPageFormTrait
{
    /**
     * @return array
     */
    public function getControllerRouteOptions()
    {
        $routesMap = Yii::$app->getModule('cms')->availableControllerRoutes;
        $dropdownOptions = $boundActionOptions = array();

        // Loop controllers
        foreach ($routesMap as $controllerId => $config) {

            // Loop controller actions:
            foreach ($config['actions'] as $actionId => $action_config) {
                $actionPathKey = $controllerId . '/' . $actionId;
                // Can haz action parameters? ^^
                if (is_array($action_config) and isset($action_config['params'])) {
                    if (is_array($action_config['params'])) {
                        $boundActionOptions[$actionPathKey] = $action_config['params'];
                    } else {
                        $boundActionOptions[$actionPathKey] = array($action_config['params']);
                    }
                }
                $dropdownOptions[] = array(
                    'group' => $config['label'],
                    'value' => $actionPathKey,
                    'label' => (!is_array($action_config) ? $action_config : $action_config['label']),
                );
            }
        }

        return array(
            'controllerActions' => $dropdownOptions,
            'actionParameters' => $boundActionOptions,
        );
    }
}