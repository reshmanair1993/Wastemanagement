<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class StatesController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\State';  
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
	public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','delete','list'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }

             return $actions;
     }
    public function actionIndex(){
        $query = State::getAllQuery();
        $dataProvider =  new ActiveDataProvider([
          'query' => $query
        ]);
        $models = $dataProvider->getModels();
        $ret = [];
        foreach($models as $model) {
          $ret[] = [
            'id' => $model->id,
            'name' => $model->name,
            'code' => $model->code,
            'status' => $model->status,
            'sort_order' => $model->sort_order,
          ];

        }
        return $ret;
      }
}
