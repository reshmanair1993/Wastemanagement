<?php

namespace backend\controllers;

use Yii;
use backend\models\AccountServiceRequest;
use backend\models\Ward;
use backend\models\Account;
use backend\models\Lsgi;
use backend\models\Customer;
use backend\models\PaymentRequest;
use backend\models\PaymentRequestSearch;
use backend\models\Service;
use backend\models\LsgiServiceSlabFee;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use yii\helpers\Json;
use backend\components\AccessPermission;
use kartik\mpdf\Pdf;
/**
 * KitchenBinRequestsController implements the CRUD actions for KitchenBinRequest model.
 */
class InvoiceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'only'         => ['index', 'generate'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules'        => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'permissions' => ['invoice-index']
                    ],
                    [
                        'actions' => ['generate'],
                        'allow'   => true,
                        'permissions' => ['invoice-generate']
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
     * Lists all KitchenBinRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
       $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to'
        ];
        $newParams = [];
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
        $keyword       =isset($params['name'])?$params['name']:null;
        $district      = isset($params['district'])?$params['district']:null;
        $ward          = isset($params['ward'])?$params['ward']:null;
        $lsgi          = isset($params['lsgi'])?$params['lsgi']:null;;
        $from          = isset($params['from'])?$params['from']:null;
        $to            = isset($params['to'])?$params['to']:null;
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
        $searchModel = new PaymentRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to);
         $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations
        ]);
    }
    public function actionGenerate($id)
    {
        $advanceAmount =0;
        $modelAccount = new Account;
        $modelCustomer = new Customer;
        $modelWard = new Ward;
        $modelLsgi = new Lsgi;
        $modelPaymentRequest = new PaymentRequest;
        $model = new AccountServiceRequest;
        $modelPaymentRequest = PaymentRequest::find()->where(['id'=>$id])->one();
        if($modelPaymentRequest){
        $model = AccountServiceRequest::find()->where(['id'=>$modelPaymentRequest->account_service_request_id])->one();
        if($model&&$model->service_estimate)
        {
            $serviceEstimate = unserialize($model->service_estimate);
        }
        else
        {
            $serviceEstimate = null;
        }

         if($serviceEstimate){
           foreach ($serviceEstimate as $key => $value) {
          if($value['slab']==null&&$value['estimated_qty_kg']!=null){
          $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['service_id'=>$value['id']])->andWhere(['collection_interval'=>$value['collection_interval']])
          ->andWhere(['is','slab_id',null])->andWhere(['status'=>1])->one();
        }
        else
        {
           $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()
              ->where(['lsgi_service_slab_fee.collection_interval'=>$value['collection_interval']])
              ->andWhere(['<','lsgi_service_slab_fee.start_value',$value['estimated_qty_kg']])
              ->andWhere(['>','lsgi_service_slab_fee.end_value',$value['estimated_qty_kg']])
              ->andWhere(['lsgi_service_slab_fee.service_id'=>$value['id']])
              ->andWhere(['lsgi_service_slab_fee.slab_id'=>$value['slab']])
              ->one();
        }
          if($modelLsgiServiceSlabFee)
          {
            if($value['type']==1){
            if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount + ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
            }
            else
            {
              $advanceAmount =  $advanceAmount + $modelLsgiServiceSlabFee->amount*1.5;
            }
          }
        else
        {
          if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount - ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
            }
            else
            {
              $advanceAmount =  $advanceAmount -$modelLsgiServiceSlabFee->amount*1.5;
            }
        }
        }
      }
}
        $this->layout = "memo-layout";
        $modelAccount = $this->findModel(Yii::$app->user->identity->id);
        if($modelAccount){
        $modelCustomer = $modelAccount->fkCustomer;
        if($modelCustomer)
        {
            $modelWard = $modelCustomer->fkWard;
            if($modelWard)
            {
                $modelLsgi = $modelWard->fkLsgi;
            }
        }
        }
    }
        return $this->render('generate-invoice',[
        'model' => $model,
        'modelAccount' => $modelAccount,
        'modelCustomer' => $modelCustomer,
        'modelWard' => $modelWard,
        'modelLsgi' => $modelLsgi,
        'serviceEstimate' => $serviceEstimate,
        'advanceAmount' => $advanceAmount,
        'modelPaymentRequest' => $modelPaymentRequest,

      ]);
      
    }
     protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

      public function actionDownloadPdf($id)
    {
        $advanceAmount =0;
        $modelAccount = new Account;
        $modelCustomer = new Customer;
        $modelWard = new Ward;
        $modelLsgi = new Lsgi;
        $modelPaymentRequest = new PaymentRequest;
        $model = new AccountServiceRequest;
        $modelPaymentRequest = PaymentRequest::find()->where(['id'=>$id])->one();
        if($modelPaymentRequest){
        $model = AccountServiceRequest::find()->where(['id'=>$modelPaymentRequest->account_service_request_id])->one();
        if($model&&$model->service_estimate)
        {
            $serviceEstimate = unserialize($model->service_estimate);
        }
        else
        {
            $serviceEstimate = null;
        }

         if($serviceEstimate){
           foreach ($serviceEstimate as $key => $value) {
          if($value['slab']==null&&$value['estimated_qty_kg']!=null){
          $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['service_id'=>$value['id']])->andWhere(['collection_interval'=>$value['collection_interval']])
          ->andWhere(['is','slab_id',null])->andWhere(['status'=>1])->one();
        }
        else
        {
           $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()
              ->where(['lsgi_service_slab_fee.collection_interval'=>$value['collection_interval']])
              ->andWhere(['<','lsgi_service_slab_fee.start_value',$value['estimated_qty_kg']])
              ->andWhere(['>','lsgi_service_slab_fee.end_value',$value['estimated_qty_kg']])
              ->andWhere(['lsgi_service_slab_fee.service_id'=>$value['id']])
              ->andWhere(['lsgi_service_slab_fee.slab_id'=>$value['slab']])
              ->one();
        }
          if($modelLsgiServiceSlabFee)
          {
            if($value['type']==1){
            if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount + ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
            }
            else
            {
              $advanceAmount =  $advanceAmount + $modelLsgiServiceSlabFee->amount*1.5;
            }
          }
        else
        {
          if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount - ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
            }
            else
            {
              $advanceAmount =  $advanceAmount -$modelLsgiServiceSlabFee->amount*1.5;
            }
        }
        }
      }
}
        $this->layout = "memo-layout";
        $modelAccount = $this->findModel(Yii::$app->user->identity->id);
        if($modelAccount){
        $modelCustomer = $modelAccount->fkCustomer;
        if($modelCustomer)
        {
            $modelWard = $modelCustomer->fkWard;
            if($modelWard)
            {
                $modelLsgi = $modelWard->fkLsgi;
            }
        }
        }
    }
        $content = $this->renderPartial('generate-invoice-pdf',[
        'model' => $model,
        'modelAccount' => $modelAccount,
        'modelCustomer' => $modelCustomer,
        'modelWard' => $modelWard,
        'modelLsgi' => $modelLsgi,
        'serviceEstimate' => $serviceEstimate,
        'advanceAmount' => $advanceAmount,
        'modelPaymentRequest' => $modelPaymentRequest,

      ]);
    
    $pdf = new Pdf([
        // set to use core fonts only
        'mode' =>  Pdf::MODE_UTF8, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_DOWNLOAD, 
        // your html content input
        'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        // any css to be embedded if required
        'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Offer Letter'],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>[''], 
            'SetFooter'=>[''],
        ]
        ]);
    // return the pdf output as per the destination setting
    return $pdf->render();
      
    }

}
