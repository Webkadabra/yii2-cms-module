<?php
namespace webkadabra\yii\modules\cms;
use yii;
use yii\base\Exception;

/**
 * Class CmsPageTrait
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms
 */
trait CmsPageTrait
{
    protected $modifiedProperties = null;
    /**
     * @param $key
     * @return null|string a value from serialized data in 'nodeProperties' field
     */
    public function loadOption($key)
    {
        $this->unpackOptions();
        return isset($this->nodeProperties[$key]) ? $this->nodeProperties[$key] : null;
    }

    /**
     * Set option property value
     * @param  $key
     * @param  $value
     * @return void
     */
    public function setOption($key, $value)
    {
        $this->unpackOptions();
        $this->modifiedProperties[$key] = $value;
    }

    /**
     * @return void
     */
    public function unpackOptions() {
        if ($this->modifiedProperties === null) {
            if ($this->nodeProperties AND !is_array($this->nodeProperties)) {
                $value = @unserialize($this->nodeProperties);
                if (!is_array($value)) {
                    $value = (array)json_decode($this->nodeProperties, true);
                    $this->modifiedProperties = $value;
                    $this->nodeProperties = $value;
                } else {
                    $this->modifiedProperties = $value;
                    $this->nodeProperties = $value;
                }
            } else {
                $this->modifiedProperties = $this->nodeProperties;
            }
        }
    }

    /**
     * Unset any empty option, so there's no empty strings saved into serialized field
     * @return void
     */
    public function cleanUnusedOptions() {
        if ($this->modifiedProperties and !is_array($this->modifiedProperties)) {
            // @todo We could try {and !is_array($unpacked=unserialize($this->nodeProperties))}  but dont forget to serialize it back on return
            throw new Exception('nodeProperties value con not be read by model\'s garbage collector');
        }
        if ($this->modifiedProperties)
            foreach ($this->modifiedProperties as $key => $value) {
                if (!$value) {
                    unset($this->modifiedProperties[$key]);
                }
            }
    }

    public function getController_route() {
        return $this->loadOption('controller_route');
    }

    public function setController_route($value) {
        $this->setOption('controller_route', $value);
    }

    public function getAction_parameters() {
        return $this->loadOption('action_parameters');
    }

    public function setAction_parameters($value) {
        $this->setOption('action_parameters', $value);
    }

    public function getMeta_keywords() {
        return $this->loadOption('meta_keywords');
    }

    public function setMeta_keywords($value) {
        $this->setOption('meta_keywords', $value);
    }

    public function getMeta_description() {
        return $this->loadOption('meta_description');
    }

    public function setMeta_description($value) {
        $this->setOption('meta_description', $value);
    }

    public function getPage_title() {
        return $this->loadOption('page_title');
    }

    public function setPage_title($value) {
        $this->setOption('page_title', $value);
    }

    public function getBody_class() {
        return $this->loadOption('body_class');
    }

    public function setBody_class($value) {
        $this->setOption('body_class', $value);
    }
}
