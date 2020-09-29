<?php

namespace api\controllers;

use Yii;

class CategoryController extends ActiveController
{
  public $modelClass = 'api\models\Category';
  public $modelClassSearch = 'api\models\CategorySearch';
  public $findBy = 'slug'; // find by slug instead id

  public function allowedActions() {
    return ['index', 'view', 'get-tree'];
  }

  public function actionGetTree($parent_id=null) {
    return $this->modelClass::getTree($parent_id);
  }

}