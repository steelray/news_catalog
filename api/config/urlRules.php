<?php
return [

  'get-tree' => 'category/get-tree',

  [
    'class' => 'yii\rest\UrlRule',
    'controller' => [
      'category',
      'post'
    ],
    'patterns' => [
      'PUT,PATCH {id}' => 'update',
      'DELETE {id}' => 'delete',
      'GET,HEAD {id}' => 'view',
      'GET,HEAD {slug}' => 'view',
      'POST' => 'create',
      'GET,HEAD' => 'index',
      '{id}' => 'options',
      'OPTIONS' => 'options',
      'PUT,PATCH {slug}' => 'update',
    ],
    'tokens' => [
      '{id}' => '<id:\\d[\\d,]*>',
      '{slug}' => '<slug:\\w[\\w,]*>',
    ],
  ],
];