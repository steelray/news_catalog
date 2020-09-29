<?php
namespace api\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\auth\CompositeAuth;

/**
 * 
 */
class ActiveController extends \yii\rest\ActiveController
{

  // actions that are available for unauthorized users
  public function allowedActions() {
    return [];
  }


  // remove unused actions from activecontroller
  public function unsetActions() {
    return [
    ];
  }
  
  public function behaviors()
  {
    $behaviors = parent::behaviors();

    $behaviors['authenticator'] = [
        'class' => CompositeAuth::className(),
        'authMethods' => [
            HttpBearerAuth::className(),
        ],
    ];

    $behaviors['authenticator']['except'] = $this->allowedActions();

    $behaviors['verbs'] = [
        'class' => \yii\filters\VerbFilter::className(),
        'actions' => [
            'index' => ['get', 'options'],
        ],
    ];

    // remove authentication filter
    $auth = $behaviors['authenticator'];
    unset($behaviors['authenticator']);

    // add CORS filter
    $behaviors['corsFilter'] = [
      'class' => \yii\filters\Cors::className(),
      'cors' => [
        'Origin' => ['*'],
        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        'Access-Control-Request-Headers' => ['*'],
        'Access-Control-Allow-Credentials' => null,
        'Access-Control-Max-Age' => 86400,
        'Access-Control-Expose-Headers' => [
          'X-Pagination-Current-Page',
          'X-Pagination-Page-Count',
          'X-Pagination-Per-Page',
          'X-Pagination-Total-Count',
        ],
      ]
    ];

    $behaviors['authenticator'] = $auth;
    return $behaviors;
  }

  
  protected function verbs()
  {
    $verbs = parent::verbs();
    $verbs['view'] = ['GET', 'OPTIONS', 'HEAD'];
    return $verbs;
  }

  
  public function actions() {
    $actions = parent::actions();
    foreach ($this->unsetActions() as $action) {
      unset($actions[$action]);
    }
    $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

    if(isset($this->findBy) && $this->findBy == 'slug') {
      $actions['view']['class'] = 'api\actions\ViewAction';
      $actions['view']['findModel'] = [$this, 'findModel'];
    }


    return $actions;
  }

  public function findModel($slug) {
    $model = new $this->modelClass;
    $model = $model::find()->where(['slug' => $slug]);
    if(!$model->count()) {
      throw new \yii\web\NotFoundHttpException('Post not found');
    }
    return $model->one();
  }  
 
  public function prepareDataProvider() {
    $searchModel = new $this->modelClassSearch;
    return $searchModel->search(Yii::$app->request->queryParams);
  }

}