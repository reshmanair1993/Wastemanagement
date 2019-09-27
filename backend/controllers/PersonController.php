<?php

namespace backend\controllers;

use Yii;
use backend\models\Account;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\Person;


/**
 * DistrictsController implements the CRUD actions for District model.
 */
class PersonController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all District models.
     * @return mixed
     */
    public function actionIndex()
    {
       // $dataProvider = new ActiveDataProvider(
       //  [
       //     'query' => Person::getAllQuery()->andWhere(['role' => 'surveyor','status' => '1']),
       //     'pagination' => false,
       //     'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
       //  ]);
        return $this->render('index');
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          if($personOk){
            $modelPerson->save(false);
            return $this->redirect(['index']);
          }
        }


        return $this->render('create', [
            'modelPerson' => $modelPerson,
        ]);
    }


    public function actionUpdate($id)
    {
        $modelPerson = $this->findModel($id);

        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          if($personOk){
            $modelPerson->save(false);
            return $this->redirect(['index']);
          }
        }
        return $this->render('update', [
          'modelPerson' => $modelPerson,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteAccount($id)
    {
        $model = new Person;
        $model->deletePerson($id);
    }
}
