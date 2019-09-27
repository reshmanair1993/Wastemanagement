<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\District;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class DistrictsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\District';

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
      public function actionIndex($state_id = null){
        $query = District::getAllQuery()->orderby('district.sort_order ASC');;
        if($state_id) {
          $query->andWhere(['state_id' => $state_id]);
        }
        $dataProvider =  new ActiveDataProvider([
          'query' => $query
        ]);
        $models = $dataProvider->getModels();
        $ret = [];
        foreach($models as $model) {
          $ret[] = [
            'id' => $model->id,
            'name' => $model->name,
            'sort_order' => $model->sort_order,
          ];

        }
        return $ret;
      }

}
