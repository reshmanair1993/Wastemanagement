<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use backend\models\CmsPostTypes;
use backend\models\CmsPost;
use backend\models\CmsSettings;
use backend\models\CmsPages;
use backend\models\Labels;
use yii\data\ActiveDataProvider;

class CmsComponent extends Component
{
    public function getPostsDataProvider($post_type){
      $modelPost = new CmsPost;
      $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelPost->getAllQuery($post_type),
                // 'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
     return $dataProvider;
    }
     public function getSettings(){
      $modelSettings = CmsSettings::find()->where(['status'=>1])->one();
      if($modelSettings)
      {
        return $modelSettings;
      }
      else
      {
        return null;
      }
     
    }
    public function getPost($slug){
      $modelPost = CmsPost::find()->where(['status'=>1])->andWhere(['slug'=>$slug])->one();
      if($modelPost)
      {
        return $modelPost;
      }
      else
      {
        return null;
      }
     
    }
    public function getPage($name){
      $modelPage = CmsPages::find()->where(['status'=>1])->andWhere(['name'=>$name])->one();
      if($modelPage)
      {
        return $modelPage;
      }
      else
      {
        return null;
      }
     
    }
   public function getLabel($name){
      $modelLabel = Labels::find()->where(['status'=>1])->andWhere(['name'=>$name])->one();
      if($modelLabel)
      {
        return $modelLabel;
      }
      else
      {
        return null;
      }
     
    }

}
