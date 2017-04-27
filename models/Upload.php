<?php

namespace fgh151\upload\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "upload".
 *
 * @property integer $id
 * @property string $fsFileName
 * @property string $virtualFileName
 *
 */
class Upload extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fsFileName'], 'required'],
            [['fsFileName', 'virtualFileName'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'fsFileName' => Yii::t('common', 'Fs File Name'),
            'virtualFileName' => Yii::t('common', 'Virtual File Name'),
        ];
    }
}
