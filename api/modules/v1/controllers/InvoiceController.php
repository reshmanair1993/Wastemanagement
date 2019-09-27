<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Payment;
use  api\modules\v1\models\PaymentRequest;
use  api\modules\v1\models\AccountServiceRequest;
use  api\modules\v1\models\LsgiServiceSlabFee;
use  api\modules\v1\models\Lsgi;
use  api\modules\v1\models\Account;
use  api\modules\v1\models\Customer;
use  api\modules\v1\models\Image;
use  api\modules\v1\models\Ward;
use  api\modules\v1\models\Service;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\Url;

class InvoiceController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Payments';

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
      public function actionIndex($page = 1,$per_page = 30,$from= null, $to = null){
        $ret =[];
        $totalAmount = 0;
        $query = PaymentRequest::getAllQuery()->andWhere(['account_id_customer'=>Yii::$app->user->identity->id])->orderby('id DESC');
        if(isset($from)&&$from!=null)
        {
          $from = date('Y-m-d',strtotime($from));
          $query->andWhere(['>=','requested_date',$from]);
        }
        if(isset($to)&&$to!=null)
        {
          $to = date('Y-m-d',strtotime($to));
          $query->andWhere(['<=','requested_date',$to]);
        }
        $dataProvider =  new ActiveDataProvider([
          'query' => $query,
          'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]
        ]);
        $models = $dataProvider->getModels();
        $ret = [];
        foreach($models as $model) {
          $modelPayment = Payment::find()->where(['payment_request_id'=>$model->id])->andWhere(['status'=>1])->all();
          if($modelPayment)
          {
            foreach ($modelPayment as $key => $value) {
             $totalAmount = $totalAmount + $value->amount;
            }
          }
          if($totalAmount>=$model->amount)
          {
            $paid = 1 ;
          }
          else
          {
            $paid = 0;
          }
          $ret[] = [
            'id' => $model->id,
            'date' => date('Y-m-d',strtotime($model->created_at)),
            'amount' => $model->amount,
            'paid' => $paid,
          ];

        }
        return $ret;
   }
   public function actionInvoiceDetails($invoice_id=null){
        $invoice_base_url = isset(Yii::$app->params['invoice_base']) ? Yii::$app->params['invoice_base'] : null;
        $ret =[];
        $base_url = isset(Yii::$app->params['logo_image_base_url']) ? Yii::$app->params['logo_image_base_url'] : null;
        $totalAmount = 0;
        $advanceAmount = 0;
        $advanceAmountNew = 0;
        $grandTotal = 0;
        $serviceEstimateList = [];
        $modelAccount = new Account;
        $modelCustomer = new Customer;
        $modelWard = new Ward;
        $modelLsgi = new Lsgi;
        if (!isset($invoice_id))
                {
                  $msg   = ['Invoice id is mandatory'];
                  $error = ['invoice_id' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
        $modelPaymentRequest = PaymentRequest::find()->where(['id'=>$invoice_id])->andWhere(['status'=>1])->one();
        if($modelPaymentRequest)
        {
        $model = AccountServiceRequest::find()->where(['id'=>$modelPaymentRequest->account_service_request_id])->one();
        if($model)
        {
          $modelAccount = Account::find()->where(['id'=>$model->account_id])->andWhere(['status'=>1])->one();
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
        if($model&&$model->service_estimate)
        {
            $serviceEstimate = unserialize($model->service_estimate);
        }
        else
        {
            $serviceEstimate = null;
        }
           if($serviceEstimate){
          foreach ($serviceEstimate as $value) {
            if($value['id'])
            {
              $qty = $value['estimated_qty_kg'];
            if($value['slab']==0&&$value['estimated_qty_kg']!=null){
          $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['service_id'=>$value['id']])
          // ->andWhere(['collection_interval'=>$value['collection_interval']])
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
              $advanceAmount =  $advanceAmount + ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount);
            }
            else
            {
              $advanceAmountNew =  $advanceAmountNew + $modelLsgiServiceSlabFee->amount;
            }
          }
        else
        {
          if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount - ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount);
            }
            else
            {
              $advanceAmountNew =  $advanceAmountNew -$modelLsgiServiceSlabFee->amount;
            }
        }
        $advanceAmount = $advanceAmount*45;
        $advanceAmountNew = $advanceAmountNew*1.5;
        $advanceAmount = $advanceAmount + $advanceAmountNew;
        }
        $image = null;
        if($modelLsgi){
          if(isset($modelLsgi->cgst_percentage)&&isset($modelLsgi->sgst_percentage)){
          $grandTotal = $advanceAmount +(($advanceAmount*$modelLsgi->cgst_percentage)/100 )+ (($advanceAmount*$modelLsgi->sgst_percentage)/100);
        }
        else
        {
          $grandTotal = $advanceAmount;
        }
          $modelImage = $modelLsgi->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
        }
        $service = Service::find()->where(['id'=>$value['id']])->andWhere(['status'=>1])->one();
        $serviceEstimateList[] = [
                'id'   => isset($service->id)?$service->id:null,
                'name' => isset($service->name)?$service->name:null,
                'estimated_qty_kg' => $qty,
                'unit_price' => isset($modelLsgiServiceSlabFee->amount)?$modelLsgiServiceSlabFee->amount:0,
                // 'advance_amount'=>$advanceAmount
        ];
      }
        }
        }
        $ret = [
          'image_base' =>$base_url,
          'customer_name'=>$modelCustomer->lead_person_name,
          'address'=>$modelCustomer->address,
          'invoice_date'=>isset($modelPaymentRequest->created_at)?date('Y-m-d',strtotime($modelPaymentRequest->created_at)):'',
          'advance_amount' =>$advanceAmount,
          'sgst_percentage' =>isset($modelLsgi->sgst_percentage)?$modelLsgi->sgst_percentage:'',
          'cgst_percentage' =>isset($modelLsgi->cgst_percentage)?$modelLsgi->cgst_percentage:'',
          'sgst_amount' =>isset($modelLsgi->sgst_percentage)?$advanceAmount*$modelLsgi->sgst_percentage/100:'',
          'cgst_amount' =>isset($modelLsgi->cgst_percentage)?$advanceAmount*$modelLsgi->cgst_percentage/100:'',
          'grand_total' =>round($grandTotal,2),
          'image'=>$image,
          'invoice_url'=>$invoice_base_url.$invoice_id,
          'service_estimate' =>$serviceEstimateList
    ];
    return $ret
;  
      }
          else
        {
          $msg   = ['Invalid invoice id'];
          $error = ['invoice_id' => $msg];
          $ret   = ['errors' => $error];
          return $ret;
        }
        
   }
      
}
