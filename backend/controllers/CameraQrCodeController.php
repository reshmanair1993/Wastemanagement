<?php

namespace backend\controllers;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use backend\models\CameraQrCodes;
use backend\models\Lsgi;
use backend\models\Account;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use dosamigos\qrcode\formats\MailTo;
use dosamigos\qrcode\QrCode;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;


class CameraQrCodeController extends \yii\web\Controller
{
     public function behaviors()
    {

      return [
          'access' => [
              'class'        => AccessControl::className(),
              'only'         => ['index','create','update','view','view-qr-sheet'],
              'ruleConfig' => [
                      'class' => AccessPermission::className(),
                  ],
              'rules'        => [
                  [
                      'actions' => ['index'],
                      'allow'   => true,
                      'permissions' => ['camera-qr-code-index']
                  ],
                  [
                      'actions' => ['create'],
                      'allow'   => true,
                      'permissions' => ['camera-qr-code-create']
                  ],
                  [
                      'actions' => ['update'],
                      'allow'   => true,
                      'permissions' => ['camera-qr-code-update']
                  ],
                  [
                      'actions' => ['view'],
                      'allow'   => true,
                      'permissions' => ['camera-qr-code-view']
                  ],
                  [
                      'actions' => ['view-qr-sheet'],
                      'allow'   => true,
                      'permissions' => ['camera-qr-code-view-qr-sheet']
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
    // public function actionAssign()
    // {
    //   $modelQrCode = CameraQrCodes::find()->where(['not', ['customer_id' => null]])->andWhere(['status'=>1])->all();
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
                'query'      => CameraQrCodes::getAllQuerys()->andWhere(['status' => 1]),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
       $dataProvider = new ActiveDataProvider(
            [
                'query'      => CameraQrCodes::getAllQuerys()->andWhere(['status' => 1]),
                'pagination' => [
          'pageSize' => 20,
        ],
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);

        return $this->render('index', ['dataProvider' => $dataProvider,'newDataProvider' => $newDataProvider]);
    }
    public function actionCreate()
    {
        $modelQrCode = new CameraQrCodes();
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
        		$model = new CameraQrCodes;
                $modelPrevious = CameraQrCodes::find()->orderby('id DESC')->one();
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
  	return CameraQrCode::jpg($code);
}
 public function actionViewQrSheet() {
    $modelQrCode = new CameraQrCodes;
    $modelQrCode->setScenario('print-sheet');
    $qrCode = CameraQrCodes::find()->where(['account_id'=>null])
        ->andWhere(['status'=>1])
        ->orderby('id ASC')->one();
        $modelQrCode->start = $qrCode->value;
    $newDataProvider = new ActiveDataProvider(
            [
                'query'      => CameraQrCodes::getAllQuery()->andWhere(['status' => 1]),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
    return $this->render('view-sheet', ['newDataProvider' => $newDataProvider,'modelQrCode'=>$modelQrCode]);
}
public function actionPrintCodes() {
    $this->layout = 'print';
    $modelQrCode = new CameraQrCodes;
    $modelQrCode->setScenario('print-sheet');
    $params       = Yii::$app->request->get();
    $limit = isset($params['limit'])?$params['limit']:null;
    $lsgi_id = isset($params['lsgi_id'])?$params['lsgi_id']:null;
    $columns = isset($params['columns'])?$params['columns']:null;
    if(isset($params['start'])){
        if($params['start']==null)
            {
        $qrCode = CameraQrCodes::find()->where(['account_id'=>null])
        ->andWhere(['status'=>1])
        ->orderby('id ASC')->one();
        $start = $qrCode->value;
    }
    else
    {
       $start = $params['start'];
       $qr = CameraQrCodes::find()->where(['value'=>$start])->one();
       if($qr)
       {
        $start = $params['start'];
       }
       else
       {
            $qrCode = CameraQrCodes::find()->where(['account_id'=>null])
        ->andWhere(['status'=>1])
        ->orderby('id ASC')->one();
        $start = $qrCode->value;
       }
    }
    }
    $modelQrCode = new CameraQrCodes;

    $newDataProvider = new ActiveDataProvider(
            [
                'query'      => CameraQrCodes::getAllQuery($start,$limit,$lsgi_id)->andWhere(['status' => 1]),
                'pagination' => false,
                // 'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
    return $this->render('view-print', ['newDataProvider' => $newDataProvider,'modelQrCode'=>$modelQrCode,'columns'=>$columns]);
}

}
