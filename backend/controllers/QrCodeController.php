<?php

namespace backend\controllers;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use backend\models\QrCodes;
use backend\models\Lsgi;
use backend\models\Account;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use dosamigos\qrcode\formats\MailTo;
use dosamigos\qrcode\QrCode;
use yii\filters\AccessControl;
use backend\components\AccessPermission;

class QrCodeController extends \yii\web\Controller
{
     public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','generate-qr-code','generate-qr-code','print-codes'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['qr-code-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['qr-code-create'],
                   ],
                   [
                       'actions' => ['generate-qr-code'],
                       'allow' => true,
                       'permissions' => ['qr-code-generate-qr-code'],
                   ],
                   [
                       'actions' => ['generate-qr-code'],
                       'allow' => true,
                       'permissions' => ['qr-code-generate-qr-code'],
                   ],
                   [
                       'actions' => ['print-codes'],
                       'allow' => true,
                       'permissions' => ['qr-code-print-codes'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }
    // public function actionAssign()
    // {
    //   $modelQrCode = QrCodes::find()->where(['not', ['customer_id' => null]])->andWhere(['status'=>1])->all();
    //   foreach ($modelQrCode as $key => $value) {
    //     $modelAccount = Account::find()->where(['customer_id'=>$value->customer_id])->one();
    //     if($modelAccount)
    //     {
    //       $value->account_id = $modelAccount->id;
    //       $value->save(false);
    //     }
    //   }
    // }
    public function actionIndex()
    {
       $newDataProvider = new ActiveDataProvider(
            [
                'query'      => QrCodes::getAllQuerys()->andWhere(['status' => 1]),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
       $dataProvider = new ActiveDataProvider(
            [
                'query'      => QrCodes::getAllQuerys()->andWhere(['status' => 1]),
                'pagination' => [
          'pageSize' => 20,
        ],
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);

        return $this->render('index', ['dataProvider' => $dataProvider,'newDataProvider' => $newDataProvider]);
    }
    public function actionCreate()
    {
        $modelQrCode = new QrCodes();
        $params       = Yii::$app->request->post();
        $paramsOk     = $params&&$modelQrCode->load($params);

        $lsgi_id = $modelQrCode->lsgi_id;
		$modelLsgi = Lsgi::getAllQuery()->andWhere(['id'=>$lsgi_id])->one();
		$paramsOk = $paramsOk&&$modelLsgi;
        if ($paramsOk)
        {
			$code = $modelLsgi->code;
        	for($i=0;$i<$modelQrCode->limit;$i++)
        	{
        		$model = new QrCodes;
                $modelPrevious = QrCodes::find()->orderby('id DESC')->one();
				$lastId = 0;
                if($modelPrevious){
					$lastId = $modelPrevious->id;
                } 
				
                $model->value = $code.'-'.$lastId;/*.'-' . time()*/;
        		$model->lsgi_id = $lsgi_id;
        		$model->save(false);
        	}
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'modelQrCode'  => $modelQrCode,
        ]);
    }
   public function actionGenerateQrcode() {

   	$code = "test";
  	return QrCode::jpg($code);
}
 public function actionViewQrSheet() {
    $modelQrCode = new QrCodes;
    $modelQrCode->setScenario('print-sheet');
    $qrCode = QrCodes::find()->where(['account_id'=>null])
        ->andWhere(['status'=>1])
        ->orderby('id ASC')->one();
        $modelQrCode->start = $qrCode->value;
    $newDataProvider = new ActiveDataProvider(
            [
                'query'      => QrCodes::getAllQuery()->andWhere(['status' => 1]),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
    return $this->render('view-sheet', ['newDataProvider' => $newDataProvider,'modelQrCode'=>$modelQrCode]);
}
public function actionPrintCodes() {
    $this->layout = 'print'; 
    $modelQrCode = new QrCodes;
    $modelQrCode->setScenario('print-sheet');
    $params       = Yii::$app->request->get();
    $limit = isset($params['limit'])?$params['limit']:null;
    $lsgi_id = isset($params['lsgi_id'])?$params['lsgi_id']:null;
    $columns = isset($params['columns'])?$params['columns']:null; 
    if(isset($params['start'])){
        if($params['start']==null)
            {
        $qrCode = QrCodes::find()->where(['account_id'=>null])
        ->andWhere(['status'=>1])
        ->orderby('id ASC')->one();
        $start = $qrCode->value;
    }
    else
    {
       $start = $params['start']; 
       $qr = QrCodes::find()->where(['value'=>$start])->one();
       if($qr)
       {
        $start = $params['start']; 
       }
       else
       {
            $qrCode = QrCodes::find()->where(['account_id'=>null])
        ->andWhere(['status'=>1])
        ->orderby('id ASC')->one();
        $start = $qrCode->value;
       }
    }
    }
    $modelQrCode = new QrCodes;

    $newDataProvider = new ActiveDataProvider(
            [
                'query'      => QrCodes::getAllQuery($start,$limit,$lsgi_id)->andWhere(['status' => 1]),
                'pagination' => false,
                // 'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
    return $this->render('view-print', ['newDataProvider' => $newDataProvider,'modelQrCode'=>$modelQrCode,'columns'=>$columns]);
}

}
