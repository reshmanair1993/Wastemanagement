<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ResidentialAssociation;
use backend\models\AssociationType;
use backend\models\District;
use yii\helpers\ArrayHelper;
use backend\components\AccessPermission;

/**
 * Site controller
 */
class AssociationTypeController extends Controller
{
    /**
     * {@inheritdoc}
     */

     // public function behaviors()
     // {
     //     return [
     //        'access' => [
     //            'class' => AccessControl::className(),
     //            'only' => ['index','create','delete-association','view'],
     //            'rules' => [
     //                [
     //                    // 'actions' => ['index','create','delete-association','update','view'],
     //                    'allow' => true,
     //                    'roles' => ['@'],
     //                    'permissions' => [
     //                      'association-type-create','association-type-update','association-type-index',
     //                      'association-type-delete-association'
     //                    ]
     //                ],
     //            ],
     //            'denyCallback' => function($rule, $action) {
     //                return $this->goHome();
     //            }
     //        ],
     //    ];
     // }
     public function behaviors()
     {
         return [
             'access' => [
                 'class'        => AccessControl::className(),
                 'only'         => ['index','create','update','view'],
                 'ruleConfig' => [
                         'class' => AccessPermission::className(),
                     ],
                 'rules'        => [
                     [
                         'actions' => ['index'],
                         'allow'   => true,
                         'permissions' => ['association-type-index']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['association-type-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['association-type-update']
                     ],
                     [
                         'actions' => ['delete-association'],
                         'allow'   => true,
                         'permissions' => ['association-type-delete-association']
                     ],
                 ],
                 'denyCallback' => function (
                     $rule,
                     $action
                 )
                 {
                     return $this->goHome();
                 }
             ]
         ];
     }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
      $modelAssociationType = new AssociationType;
      $dataProvider = $modelAssociationType->search(Yii::$app->request->queryParams);
      $params = [
        'modelAssociationType' => $modelAssociationType,
        'dataProvider' => $dataProvider,
      ];
      return $this->render('index',[
        'params' => $params,
      ]);
    }

    public function actionCreate()
    {
      $saved = false;
      $modelAssociationType = new AssociationType;
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelAssociationType->load($params);
      if($paramsOk){
        $modelAssociationTypeOk = $modelAssociationType->validate();
        if($modelAssociationType){
          $modelAssociationType->save(false);
          // $saved = true;
          // $modelResidentialAssociation = new ResidentialAssociation;
          return $this->redirect('index');
        }
      }
      $params = [
        'modelAssociationType' => $modelAssociationType,
      ];
      return $this->render('add-association-type',[
        'params' => $params,
      ]);
    }
    public function actionUpdate($id){
      $modelAssociationType = $this->findModelAssociationType($id);
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelAssociationType->load($params);
      if($paramsOk){
        $modelAssociationTypeOk = $modelAssociationType->validate();
        if($modelAssociationTypeOk){
          $modelAssociationType->update(false);
          // $saved = true;
          // $modelResidentialAssociation = new ResidentialAssociation;
          return $this->redirect('index');
        }
      }
      $params = [
        'modelAssociationType' => $modelAssociationType,
      ];
      return $this->render('update-association-type',[
        'params' => $params,
      ]);
    }
    public function findModelAssociationType($id){
      $modelAssociationType = AssociationType::find()->where(['status'=>1,'id'=>$id])->one();
      return $modelAssociationType;
    }
    public function actionDeleteAssociation($id)
    {
      $modelAssociationType = new AssociationType;
      $modelAssociationType->deleteAssociationType($id);
    }
    /**
     * Login action.
     *
     * @return string
     */

    /**
     * Logout action.
     *
     * @return string
     */

}
