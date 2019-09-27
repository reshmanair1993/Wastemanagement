<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\ResidentialAssociation;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class ResidentialAssociationController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\ResidentialAssociation';
     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','index','delete'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }

             return $actions;
     }
	 // public function behaviors()
  //   {
  //       return [
  //           'verbs' => [
  //               'class' => VerbFilter::className(),
  //               'actions' => [
  //                   'delete' => ['POST'],
  //               ],
  //           ],
  //           'auth' => [
  //               'class' => HttpBearerAuth::className(),
  //           ]
  //       ];
  //   }
     public function actionGetResidentialAssociation($ward_id){
       $query = ResidentialAssociation::getAllQuery();
       if($ward_id) {
         $query->andWhere(['ward_id' => $ward_id])
                ->andWhere(['status'=>1]);
       }
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
           'registration_number' => $model->registration_number,
         ];

       }
       return $ret;

     }


}
