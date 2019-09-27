<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\FirebaseToken;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class FirebaseTokenController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\FirebaseToken';

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
     public function actionRegisterFcmToken()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];
        $params    = Yii::$app->request->post();

        while (true)
        {
                if (!isset($params['token']))
                {
                    $msg   = ['Token is mandatory'];
                    $error = ['token' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
            if ($params)
            {
                $modelFirebaseToken = new FirebaseToken;
          
                $modelFirebaseToken->token                  = isset($params['token']) ? $params['token'] : null;;
                $modelFirebaseToken->account_id = $userId;
                $modelFirebaseToken->save(false);
                $ret = [
                   'id' => $modelFirebaseToken->id,
                   'token' => $modelFirebaseToken->token,
                   'account_id' => $modelFirebaseToken->account_id
                ];
            }
            break;
        }

        return $ret;
    }
    public function actionUnRegisterFcmToken()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];
        $params    = Yii::$app->request->post();

        while (true)
        {
                if (!isset($params['token']))
                {
                    $msg   = ['Token is mandatory'];
                    $error = ['token' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
            if ($params)
            {
                $modelFirebaseToken = FirebaseToken::find()->where(['account_id'=>$userId])->andWhere(['token'=>$params['token']])->andWhere(['status'=>1])->one();
                if(!$modelFirebaseToken)
                {
                    $msg   = ['Invalid token'];
                    $error = ['token' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }else
                {
                $modelFirebaseToken->status = 0;
                $modelFirebaseToken->save(false);
                $ret = [
                   'status' => 'success',
                ];
            }
          }
            break;
        }

        return $ret;
    }


}
