<?php

use yii\db\Migration;

/**
 * Handles the creation of table `upload`.
 */
class m161228_112948_create_upload_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('upload', [
            'id' => $this->primaryKey(),
            'fsFileName' => $this->string()->notNull(),
            'virtualFileName' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('upload');
    }
}
