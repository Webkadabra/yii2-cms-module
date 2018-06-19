<?php

namespace webkadabra\yii\modules\cms;

use \yii;
/**
 * Class Module
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'webkadabra\yii\modules\cms\controllers';

    public function init()
    {
        parent::init();
        $this->layoutPath = Yii::getAlias('@app/views/layouts'); // use application-level layouts, not module level;
    }
}
