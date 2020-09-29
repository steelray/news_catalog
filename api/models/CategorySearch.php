<?php
namespace api\models;
use Yii;
use \yii\helpers\Url;
use yii\data\ActiveDataProvider;
/**
 * 
 */
class CategorySearch extends \common\models\Category
{
  public function rules()
  {
    return [
      [['title'], 'string'],
      ['parent_id', 'integer'],
      ['title', 'filter', 'filter' => function($value) {
        return strip_tags(htmlspecialchars($value));
      }],
    ];
  }


  public function search($params)
  {
    $page = Yii::$app->getRequest()->getQueryParam('page');
    $limit = Yii::$app->getRequest()->getQueryParam('limit');

    $limit = isset($limit) ? $limit : 50;
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

    $query->andFilterWhere([
      'parent_id' => $this->parent_id
    ]);

    $query->andFilterWhere(['like', 'title', $this->title]);

    
    return $dataProvider;
  }

}