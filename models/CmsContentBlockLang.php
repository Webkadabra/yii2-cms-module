<?php

namespace webkadabra\yii\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_content_blocks_lang".
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 *
 * @property string $id
 * @property string $contenct_block_id
 * @property string $language
 * @property string $content
 */
class CmsContentBlockLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_content_blocks_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contenct_block_id'], 'required'],
            [['contenct_block_id'], 'integer'],
            [['content'], 'string'],
            [['language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contenct_block_id' => 'Contenct Block ID',
            'language' => 'Language',
            'content' => 'Content',
        ];
    }
}
