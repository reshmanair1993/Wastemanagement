<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Labels;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;
use Yii;

class LabelsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Labels';

     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','delete','index'];
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
      public function actionIndex(){
        $query = Labels::getAllQuery()->orderby('id ASC');;
        $dataProvider =  new ActiveDataProvider([
          'query' => $query,
          'pagination'=>false
        ]);
        $models = $dataProvider->getModels();
        $ret = [];
        foreach($models as $model) {
          $ret[] = [
            'id' => $model->id,
            'name' => $model->name,
            'english' => $model->english_translation,
            'malayalam' => $model->malayalam_translation,
          ];

        }
        return $ret;
      }
      
}
