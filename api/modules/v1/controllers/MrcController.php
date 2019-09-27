<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Mrc;
use  api\modules\v1\models\Image;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class MrcController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Mrc';
     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','index','delete'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }
             return $actions;
     }
	 public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'auth' => [
                'class' => HttpBearerAuth::className(),
            ]
        ];
    }
     public function actionIndex($lat,$lng,$radius=5000,$keyword=null){
       $image_base    = isset(Yii::$app->params['mrc_base_url']) ? Yii::$app->params['mrc_base_url'] : null;
        if(!isset($lat)) {
        $msg = ['Latitude is mandatory'];
        $error = ['lat'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
        }
        if(!isset($lng)) {
        $msg = ['Longitude is mandatory'];
        $error = ['lng'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
        }
        // if(!isset($radius)) {
        // $msg = ['Radius is mandatory'];
        // $error = ['radius'=>$msg];
        // $ret = ['errors' =>$error];
        // return $ret;
        // }
        if(!$keyword){
         $qry = "SELECT
  (((acos(sin(('$lat'*pi()/180)) * sin((`lat`*pi()/180))+cos(('$lat'*pi()/180))
   * cos((`lat`*pi()/180)) * cos((('$lng'- `lng`)*pi()/180))))*180/pi())*60*1.1515)
   AS distance, lat as lat , lng as lng, phone1 as phone1, phone2 as phone2, image_id as image_id, name as name,id as id, status as status from mrc having distance<=:radius and status=1";
   // having distance<=:radius
                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':radius',$radius);
    }
    else
    {
       $qry = "SELECT
  (((acos(sin(('$lat'*pi()/180)) * sin((`lat`*pi()/180))+cos(('$lat'*pi()/180))
   * cos((`lat`*pi()/180)) * cos((('$lng'- `lng`)*pi()/180))))*180/pi())*60*1.1515)
   AS distance, lat as lat , lng as lng, phone1 as phone1, phone2 as phone2, image_id as image_id, name as name,id as id, status as status from mrc having distance<=:radius and name like :keyword and status=1";
   // having distance<=:radius
                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':radius',$radius);
                  $command->bindParam(':keyword',$keyword);
    }
                  $list = $command->queryAll();
    $ret = [];
    if(count($list)>=1){
       foreach($list as $model) {
        $imagesArray =[];
        $images = json_decode($model['image_id']);
        foreach ($images as $value) {
          $modelImage = Image::find()->where(['id'=>$value])->andWhere(['status'=>1])->one();
          if($modelImage)
          $imagesArray[]= $modelImage->uri_full;
        }
        $phone =[];
        $phone[]= $model['phone1'];
        $phone[]= $model['phone2'];
         $ret[] = [
           'id' => $model['id'],
           'name' => $model['name'],
           'lat' => floatval($model['lat']),
           'lng' => floatval($model['lng']),
           'phones' => $phone,
           'images'=>$imagesArray
         ];

       }
     }
       return $ret = [
            'image_base'    => $image_base,
            'items'         => $ret
        ];
     }
     
}
