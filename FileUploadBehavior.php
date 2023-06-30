<?php
/**
 * Created by PhpStorm.
 * User: fgorsky
 * Date: 28.12.16
 * Time: 14:26
 * @property \yii\base\Component $owner
 */

namespace fgh151\upload;

use fgh151\upload\models\Upload;
use yii\base\Behavior;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileUploadBehavior extends Behavior
{
    /** @var  string */
    public $storageClass;
    /** @var  string */
    public $attribute;
    /** @var  string */
    public $folder;
    /** @var  string */
    public $storageAttribute;
    /** @var string  */
    public $storageUploadAttribute = 'uploadId';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'upload',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'upload'
        ];
    }

    /**
     * Загрузка файлов
     * @throws InvalidParamException
     * @throws Exception
     */
    public function upload()
    {
        /** @var Model $this ->owner */
        $files = UploadedFile::getInstances($this->owner, $this->attribute);
        $storageAttribute = $this->storageAttribute;
        $uploadAttribute = $this->storageUploadAttribute;

        foreach ($files as $file) {

            $path = $this->folder . DIRECTORY_SEPARATOR . substr(md5($file->name), 0, 2) . DIRECTORY_SEPARATOR . $this->owner->id;

            $path = \Yii::getAlias($path);

            FileHelper::createDirectory($path);
            
            $fileName = $file->baseName;
            if ($file->extension) {
                $fileName .= '.' . $file->extension;
            } 
            
            $file->saveAs($path . '/' . $fileName);
            $upload = new Upload();
            $upload->fsFileName = $fileName;
            $upload->save();

            $storage = new $this->storageClass();
            /** @var ActiveRecord $storage */
            $storage->$storageAttribute = $this->owner->id;
            $storage->$uploadAttribute = $upload->id;
            $storage->save();
        }
    }

}
