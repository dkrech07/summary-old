<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230323_074108_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'y_key_id' => $this->text(),
            'y_secret_key' => $this->text(),
            'bucket_name' => $this->text(),
        ], $tableOptions);

        $this->createTable('status', [
            'id' => $this->primaryKey(),
            'status_title' => $this->string(256)->notNull(),
        ]);

        $this->createTable('{{%summary}}', [
            'id' => $this->primaryKey(),
            'number' => $this->integer()->notNull(),
            'summary_status' => $this->integer()->notNull(),
            'title' => $this->string(256)->notNull(),
            'file' => $this->text(),
            'detail' => $this->text(),
            'summary' => $this->text(),
            'created_user' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'summary_status',
            'summary',
            'summary_status',
            'status',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'created_user',
            'summary',
            'created_user',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'user_id',
            'account',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
        $this->dropTable('summary');
        $this->dropTable('status');
    }
}
