<?php

use yii\db\Migration;

/**
 * Class m200928_130040_post
 */
class m200928_130040_post extends Migration
{
    const POST_TABLE = '{{%post}}';
    const POST_CATEGORY_TABLE = '{{%post_category}}';
    const POST_CATEGORY_POST_TABLE = '{{%post_category_post}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::POST_CATEGORY_TABLE, [
            'id' => $this->primaryKey(),
            'title' => $this->string(75)->notNull(),
            'slug' => $this->string(75)->notNull(),
            'parent_id' => $this->integer()->defaultValue(null),
        ], $tableOptions);
        
        $this->createIndex('post_category_slug', self::POST_CATEGORY_TABLE, 'slug');
        $this->createIndex('post_category_parent', self::POST_CATEGORY_TABLE, 'parent_id');

        $this->createTable(self::POST_TABLE, [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'content' => $this->text(),
        ], $tableOptions);

        $this->createIndex('post_slug', self::POST_TABLE, 'slug');


        $this->createTable(self::POST_CATEGORY_POST_TABLE, [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'category_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_post_category_post_id', self::POST_CATEGORY_POST_TABLE, 'post_id', self::POST_TABLE, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_post_category_category_id', self::POST_CATEGORY_POST_TABLE, 'category_id', self::POST_CATEGORY_TABLE, 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_post_category_post_id', self::POST_CATEGORY_POST_TABLE);
        $this->dropForeignKey('fk_post_category_category_id', self::POST_CATEGORY_POST_TABLE);

        $this->dropTable(self::POST_CATEGORY_POST_TABLE);
        $this->dropTable(self::POST_CATEGORY_TABLE);
        $this->dropTable(self::POST_TABLE);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200928_130040_post cannot be reverted.\n";

        return false;
    }
    */
}
