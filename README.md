Uploud system for yii2 projects
===============================
Uploud system for yii2 projects

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist fgh151/yii2-upload-behavior "*"
```

or add

```
"fgh151/yii2-upload-behavior": "*"
```

to the require section of your `composer.json` file.

Update database schema

```bash
php yii migrate/up --migrationPath=@vendor/fgh151/yii2-upload-behavior/migrations
```


Usage
-----

For example we have User model, which need photo.
Create table and model ```UserLinkPhoto```. Sample code:
```php
<?php

namespace common\models\user;

use common\models\user\User;
use fgh151\upload\models\Upload;
use Yii;

/**
 * This is the model class for table "userLinkPhoto".
 *
 * @property int $userId
 * @property int $uploadId
 *
 * @property User $user
 * @property Upload $photo
 */
class UserLinkPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userLinkPhoto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'uploadId'], 'required'],
            [['userId', 'uploadId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'uploadId' => 'Upload ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'doctorId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Upload::className(), ['id' => 'uploadId']);
    }
}
```

This class will store mapping entity and upload file.
Then add behavior to User model:

```php

/**
 * This field need fo form and validation
 */
public $imagesField;

public function behaviors()
{
    return [
        [
            'class' => FileUploadBehavior::className(), //Behavior class
            'attribute' => 'imagesField',
            'storageClass' => UserLinkPhoto::className(), //Mapping class
            'storageAttribute' => 'userId', //Entity indefier in mapping clas
            'folder' => 'user' //folder on server where store files, example '@frontend/web/upload/user' 
        ]
    ];
}
```

Now you can access files via hasMany property:
 
```php
public function getPhoto()
{
    return $this->hasMany(Upload::className(), ['id' => 'uploadId'])
        ->viaTable(UserLinkPhoto::tableName(), ['userId' => 'id']);
}
```

Or direct request:

```php
public function getPhotoPath()
{
    return Yii::getAlias('@frontend') . '/web/upload/user/' .
        substr(md5($this->photo->fsFileName), 0, 2) .
        '/' . $this->id . '/' . $this->photo->fsFileName;
}
```

Form field example:
```php
<?= $form->field($model, 'imagesField[]')->fileInput() ?>
```
