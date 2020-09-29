<?php
namespace api\models;
use Yii;
use \yii\helpers\Url;
use yii\data\ActiveDataProvider;
/**
 * 
 */
class PostSearch extends Post
{
  public $category_id;

  public function rules()
  {
    return [
      ['category_id', 'integer'],
      [['title'], 'string'],
      ['title', 'filter', 'filter' => function($value) {
        return strip_tags(htmlspecialchars($value));
      }],
    ];
  }


  public function search($params)
  {
    $page = Yii::$app->getRequest()->getQueryParam('page');
    $limit = Yii::$app->getRequest()->getQueryParam('limit');

    $limit = isset($limit) ? $limit : 20;
    $page = isset($page) ? $page : 0;

    $query = self::find();

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => $limit,
        'page' => $page
      ],
      'sort' => [
        'defaultOrder' => [
          'id' => SORT_ASC,
        ],
      ],
    ]);

    $this->attributes = $params;


    if (!$this->validate()) {
      throw new \yii\web\BadRequestHttpException(json_encode($this->errors));
    }

    if($this->category_id) {

      $categoryIds = array_merge(Category::getTreeIds(Category::getTree($this->category_id)), [$this->category_id]);

      $query->joinWith('categories')
        ->where([Category::tableName().'.id' => $categoryIds]);
    }

    $query->andFilterWhere(['like', 'title', $this->title]);

    
    return $dataProvider;
  }

}