<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "post_category".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $parent_id
 *
 * @property PostCategoryPost[] $postCategoryPosts
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_category';
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
            [['parent_id', 'parent_id'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 75],
            ['slug', 'unique']
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
            'parent_id' => 'Parent',
        ];
    }

    /**
     * Gets query for [[PostCategoryPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('{{%post_category_post}}', ['category_id' => 'id']);
    }

    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    public function getChildren() {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    public static function getTree($parent_id = null) {
        $allCategories = self::find()
            ->asArray()
            ->all();
        return self::buildTree($allCategories, $parent_id);
    }

    public static function buildTree(array $categories, $parent_id = null) {
        $res = [];
        foreach ($categories as $category) {
            if($category['parent_id'] == $parent_id) {
                $children = self::buildTree($categories, $category['id']);
                if($children) {
                    $category['children'] = $children;
                }
                $res[] = $category;
            }
        }
        return $res;
    }

    public static function getTreeIds(array $tree) {
        $ids = [];
        foreach ($tree as $arr) {
            $ids[] = $arr['id'];
            if(isset($arr['children'])) {
                $ids = array_merge($ids, self::getTreeIds($arr['children']));
            }
        }
        return $ids;
    }

}
