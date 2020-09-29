<?php
namespace api\models;

/**
 * 
 */
class Post extends \common\models\Post
{
  public function fields() {
    return [
      'slug',
      'title',
      'content',
    ];
  }

  public function extraFields() {
    return [
      'categories'
    ];
  }

  public function getCategories() {
    return parent::getCategories()->select('id, slug, title');
  }

}