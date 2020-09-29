<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 *
 * @property PostCategoryPost[] $postCategoryPosts
 */
class Post extends \yii\db\ActiveRecord
{

    public $category_id;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }


    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['title', 'slug'], 'string', 'max' => 255],
            ['slug', 'unique'],
            ['category_id', 'each', 'rule' => ['integer']],
            ['category_id', 'each', 'rule' => ['exist', 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'content' => 'Content',
        ];
    }

    /**
     * Gets query for [[PostCategoryPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('{{%post_category_post}}', ['post_id' => 'id']);
    }

    public function init() {
        parent::init();
      
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'saveCategories']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveCategories']);

    }

    public function saveCategories() {
        // unlink|remove current cats 
        Yii::$app->db->createCommand('DELETE FROM post_category_post WHERE post_id='.$this->id)->execute();
        
        $postId = $this->id;
        
        $rowsToInsert = array_map(function($catId) use ($postId) {
            return [$postId, $catId];
        }, $this->category_id);

        // insert new cats
        Yii::$app->db->createCommand()->batchInsert('post_category_post', ['post_id', 'category_id'], $rowsToInsert)->execute();

    }
}
