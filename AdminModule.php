<?php

namespace webkadabra\yii\modules\cms;
/**
 * Class Module
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms
 */
class AdminModule extends \yii\base\Module
{
    public $allowedRoles = ['admin'];

    public $availableControllerRoutes=[];

    public $controllerNamespace = 'webkadabra\yii\modules\cms\adminControllers';

    /**
     * @var string Path to main application's view path. Should be explicitly defined for advanced applications with different backend/frontend apps
     */
    public $frontendViewTemplatesPath = '@frontend/views/cms-templates';

    public function getTemplatesViewsPath()
    {
        $viewPath = $this->frontendViewTemplatesPath;
        $viewPath = \Yii::getAlias($viewPath);
        $path = realpath($viewPath);
        return $path;
    }

    /**
     * Scan templates folder and return templates and parse their configs (PhpDoc-styled)
     * @return array
     */
    public function templateListWithConfigs()
    {
        $templates = array();
        $viewPath = $this->getTemplatesViewsPath();
        $fileLists = \yii\helpers\FileHelper::findFiles($viewPath,['only'=>[
            '*.php',
        ]]);
        foreach ($fileLists as $value) {
            // For each file we are trying to read first comments block for template configuration
            $tokens = token_get_all(file_get_contents($value));
            $viewFilename = basename($value);
            if (!$tokens) {
                continue;
            }
            $view_id = str_ireplace($viewPath, '', $value);
            $view_id = str_ireplace(DIRECTORY_SEPARATOR, '/', $view_id);
            $view_id = trim($view_id, DIRECTORY_SEPARATOR);
            $view_id = str_replace('.php', '', $view_id);
            $view_id = ltrim($view_id, '/');
            if ($viewFilename AND strpos($viewFilename, '.php')) {
                $template = array();
                foreach ($tokens as $token) {
                    // Template's first PhpDoc comment block is considered template config
                    if ($token[0] == T_DOC_COMMENT) {
                        // Fetch template label
                        preg_match("/(?<=\*)\sTemplate:([A-Za-z0-9_\s]+)(?=\n)/", $token[1], $nameMatch);
                        if (!empty($nameMatch[1])) {
                            $template['label'] = trim($nameMatch[1]);
                        }
                        // Fetch template block tokens
                        preg_match("/(?<=\*)\sBlocks:([A-Za-z0-9_\s,\(\):]+)(?=\n)/", $token[1], $cellsMatch);
                        if (!empty($cellsMatch[1])) {
                            if ($cells = explode(',', $cellsMatch[1])) {
                                foreach ($cells as $cell) {
                                    $cell = trim($cell);
                                    if (strstr($cell, ':')) {
                                        $c = explode(':', $cell);
                                        $cell = ['id' => $c[0], 'hint' => $c[1]];
                                    }
                                    $template['blocks'][] = $cell;
                                }
                            }
                        }
                        break; // We are not looking for more configs in one template
                    }
                }
                if ($template) {
                    $templates[$view_id] = $template;
                }
            }
        }
        return $templates;
    }
}
