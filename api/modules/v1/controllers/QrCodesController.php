<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use  api\modules\v1\models\QrCode;
use  api\modules\v1\models\Customer;
use  api\modules\v1\models\Account;
use  api\modules\v1\models\Mrc;


class QrCodesController extends Controller

{ 

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
      public function actionIndex($account_id=null,$code=null){
		$posts = Yii::$app->request->post();
    $get = Yii::$app->request->get();
    $post = array_merge($posts,$get);
		$params = ['account_id','code']; 
		foreach($params as $param) {
			if(isset($post[$param])) {
				$$param = $post[$param];
			}
      else {
				
			   Yii::$app->response->statusCode = 401;
			   $errors = [
				 'errors' => [
				   $param =>  "$param is mandatory",
				 ],
			   ];
			   return $errors;
			}
		}
		$ok = true;
        $modelUser = Yii::$app->user->identity;
        $userId = $modelUser->id;
		$modelAccount = Account::getAllQuery()->andWhere(['id'=>$account_id])->andWhere(['role'=>'customer'])->one();
		if(!$modelAccount)$ok= false;
		if($ok) {
			$customer_id = $modelAccount->customer_id;
			$modelCustomer = Customer::getAllQuery()->andWhere(['id'=>$customer_id])->one();
			if($modelCustomer) $ok = false;
		}
	
		
		if($ok) {
           Yii::$app->response->statusCode = 401;
           $errors = [
             'errors' => [
               'code' => ['Incorrect customer id'],
             ],
           ];
           return $errors;
		}
        $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code,'account_id' => null ,'mrc_id' => null])->one();
        if($modelQrCode){
          $modelQrCode->customer_id = $customer_id;
          $modelQrCode->account_id = $modelAccount->id;
          $modelQrCode->save(false);
          $modelCustomer->qr_code_id = $modelQrCode->id;
          $modelCustomer->save(false);
		  $ret = ['status'=>'success'];
		  return $ret;
        }
        else {
           Yii::$app->response->statusCode = 401;
           $errors = [
             'errors' => [
               'code' => ['Incorrect code'],
             ],
           ];
           return $errors;
         }
       }public function actionCheckQrCodeRequest($code = null)
    {
        if ($code)
        {
          $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code,'account_id' => null ])->andWhere(['status'=>1])->one();
        if($modelQrCode){
      $ret = ['status'=>'success'];
      return $ret;
    }else
    {
        $msg   = ['Invalid qr code'];
        $error = ['qr_code' => $msg];
        $ret   = [
        'status'=>'failure',
        'errors' => $error];
        return $ret;
    }
    }else
                {
                    $msg   = ['Qr code is mandatory'];
                    $error = ['qr_code' => $msg];
                    $ret   = [
                    'status'=>'failure',
                    'errors' => $error];
                    return $ret;
                }
    }
    public function actionMrcQrCode($code=null){
    $posts = Yii::$app->request->post();
    $get = Yii::$app->request->get();
    $post = array_merge($posts,$get);
    $params = ['code']; 
    foreach($params as $param) {
      if(isset($post[$param])) {
        $$param = $post[$param];
      }
      else {
        
         Yii::$app->response->statusCode = 401;
         $errors = [
         'errors' => [
           $param =>  "$param is mandatory",
         ],
         ];
         return $errors;
      }
    }
    $ok = true;
    $modelMrc = Mrc::getAllQuery()->andWhere(['qr_code'=>$code])->one();
    if($modelMrc){
        $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code,'account_id' => null,'mrc_id' => $modelMrc->id ])->one();
        if($modelQrCode){
          // $modelQrCode->mrc_id = $mrc_id;
          // $modelQrCode->save(false);
          // $modelMrc->qr_code = $modelQrCode->value;
          // $modelMrc->save(false);
      $ret = [
      'status'=>'success',
      'id'=>$modelMrc->id
      ];
      return $ret;
        }
        else {
           Yii::$app->response->statusCode = 401;
           $errors = [
             'errors' => [
               'code' => ['Incorrect code'],
             ],
           ];
           return $errors;
         }
       }
       else
       {
        Yii::$app->response->statusCode = 401;
           $errors = [
             'errors' => [
               'code' => ['Incorrect code'],
             ],
           ];
           return $errors;
       }

}


}
?>
