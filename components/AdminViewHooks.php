<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2018-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\components;

/**
 * Admin view hooks that allow other modules & plugins to apply logic to various parts of CMS module
 *
 * ## Usage example (/backend/config/main.php):
 *
 * ```
 * 'modules' => [
 *      ...
 *      'cms' => [
 *          ...
 *          'on beforeAction' => function($event) {
 *              \yii\base\Event::on(
 *                  \webkadabra\yii\modules\cms\components\AdminViewHooks::class,
 *                  \webkadabra\yii\modules\cms\components\AdminViewHooks::APP_VIEW_LINKS_BUTTONS,
 *                  function($event) {
 *                      // add navigation link next to `View` button
 *                      array_splice( $event->buttons, -1, 0,
 *                          \yii\helpers\Html::a(Yii::t('cms', 'Navigation') . ' <i class="fa fa-exchange" aria-hidden="true"></i>',
 *                              ['/menu/menu/index', 'appId' => $event->sender->id], [
 *                                  'class' => 'btn btn-link',
 *                              ]
 *                          )
 *                      );
 *                  }
 *              );
 *          },
 *      ...
 *  ```
 *
 * @package webkadabra\yii\modules\cms\components
 */
class AdminViewHooks {
    const APP_VIEW_LINKS_BUTTONS  = 'APP_VIEW_LINKS_BUTTONS';
}