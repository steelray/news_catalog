<?php

namespace api\controllers;

use Yii;

class PostController extends ActiveController
{
  public $modelClass = 'api\models\Post';
  public $modelClassSearch = 'api\models\PostSearch';
  public $findBy = 'slug';

  public function allowedActions() {
    return ['index', 'view'];
  }

}