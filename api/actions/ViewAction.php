<?php
namespace api\actions;
use yii\rest\Action;

class ViewAction extends Action
{
  public function run($slug) {
    $model = $this->findModel($slug);
    return $model;
  }
}