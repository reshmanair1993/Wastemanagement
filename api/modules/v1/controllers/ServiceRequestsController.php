<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Account;
use api\modules\v1\models\AccountFee;
use backend\models\AccountAuthority;
use api\modules\v1\models\PaymentRequest;
use api\modules\v1\models\Service;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\ServiceRequest;
use api\modules\v1\models\ServicePackageService;
use api\modules\v1\models\ServiceAssignment;
use api\modules\v1\models\EvaluationConfigCustomerRating;
use backend\models\GreenActionUnitWard;
use backend\models\GreenActionUnit;
use api\modules\v1\models\Lsgi;
/**
 * AccountController implements the CRUD actions for Account model.
 */
class ServiceRequestsController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = '\api\modules\v1\models\Services';
    /**
     * @return mixed
     */
    public function actions()
    {
        $actions      = parent::actions();
        $unsetActions = ['create', 'update', 'index', 'delete'];
        foreach ($unsetActions as $action)
        {
            unset($actions[$action]);
        }

        return $actions;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ],
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ]
        ];
    }

    /**
     * @param  $page
     * @param  $per_page
     * @param  $type
     * @return mixed
     */
    public function actionIndex(
        $page = 1,
        $per_page = 30,
        $keyword = null,
        $type = null,$status=null,$is_special_services=null,$language=null
    )
    {
        $types      = ['service' => 1, 'complaint' => 2];
        $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $modelUser  = Yii::$app->user->identity;
        $userId     = $modelUser->id;
        $query      = ServiceRequest::getAllQuery()->andWhere(['account_id_customer' => $userId])->andWhere(['service_request.status' => 1]);
        if ($type)
        {
            $type = $types[$type];
        }

        if ($type||$keyword||isset($is_special_services))
        {
            $query->leftJoin('service', 'service_request.service_id=service.id');
        if ($type)
        {
        $query->andWhere(['service.type' => $type]);
        }
        if($keyword)
        {
            $query->andFilterWhere(['like', 'service.name', $keyword]);
        }
        if(isset($is_special_services))
        {
            if($is_special_services==1)
            $query->andWhere(['service.is_special_service' => 1]) ;
            if($is_special_services==0)
            $query->andWhere(['service.is_special_service' => 0]) ;
        }

        }
        if($status)
        {
        if($status=='completed')
         $query->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
     ->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
        if($status=='pending')
         $query->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
     ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            // 'pagination' => [
            //     'pageSize' => $per_page,
            //     'page'     => $page - 1
            // ]
            'pagination'=>false

        ]);
        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $modelServiceAssignment = $model->fkServiceAssignment;
            // if (!$modelServiceAssignment)
            // {
            //     continue;
            // }
            if ($modelServiceAssignment)
            {
                $modelServiceStatus = $modelServiceAssignment->fkServiceStatus;
            }
            else
            {
                $modelServiceStatus = null;
            }

            $modelService = $model->fkService;
            if (!$modelService)
            {
                continue;
            }

            $image      = null;
            $modelImage = $modelService->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }
            if($language&&$language=='ml')
            {
                $name = $modelService?$modelService->name_ml:null;
            }
            else
            {
                $name = $modelService?$modelService->name:null;
            }
            $ret[] = [
                'service_request_id'      => $model->id,
                'service_id'              => $model->service_id,
                'service_name'              => $name,
                'is_cancelled'              => $model->is_cancelled,
                'remarks'              => $model->remarks,
                'requested_datetime' => date('Y-m-d',strtotime($model->requested_datetime)),
                // 'ask_quantity'       => $modelService->ask_waste_quantity?$modelService->ask_waste_quantity:0,
                // 'ask_quality'        => $modelService->ask_waste_quality?$modelService->ask_waste_quality:0,
                'servicing_status_option' => $modelServiceStatus ? $modelServiceStatus->value : 'Pending',
                'rate'=>[
                     'marked_value'         => $model->marked_rating_value,
                    'total_value'         => $model->total_rating_value,
                    ],

            ];
        }

        return $ret;
    }

    /**
     * @param  $service_id
     * @return mixed
     */

    public function actionServices($id=null)
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];

        while (true)
        {
            $params = Yii::$app->request->post();
            if (isset($params['service_id']))
            {
                $modelService = Service::find()->where(['id'=>$params['service_id']])->andWhere(['status'=>1])->one();
                if(isset($modelService->is_package)&&$modelService->is_package==1&&!$id)
                {
                $services = ServicePackageService::find()->where(['service_id'=>$params['service_id']])->all();
                foreach ($services as $key => $value) {
                   $modelServiceRequest                      = new ServiceRequest;
                   $modelServiceRequest->service_id = $value->service_id_service;
                   $modelServiceRequest->account_id_customer = $userId;
                $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
                $modelServiceRequest->remarks             = isset($params['remarks']) ? $params['remarks'] : '';
                $modelAccount = Account::find()->where(['id'=>$userId])->one();
                $modelCustomer = $modelAccount->fkCustomer;
                $modelServiceRequest->ward_id = isset($modelCustomer)?$modelCustomer->ward_id:'';
                $modelServiceRequest->lsgi_id = isset($modelCustomer->ward_id)?$modelCustomer->fkWard->lsgi_id:'';
                $modelServiceRequest->service_id_package = $params['service_id'];
                $modelServiceRequest->save(false);

                }
                $modelPaymentRequest                      = new PaymentRequest;
                $modelPaymentRequest->account_id_customer = $userId;
                // $modelPaymentRequest->service_request_id = $modelServiceRequest->id;
                $modelPaymentRequest->requested_date  = date('Y-m-d H:i:s');
                $modelPaymentRequest->service_id = $params['service_id'];
                $modelPaymentRequest->amount = $modelPaymentRequest->getPackageAmount($params['service_id'],$userId);
                $modelPaymentRequest->save(false);

                }
                else{
                if($id){
                $modelServiceRequest                            = ServiceRequest::find()->where(['id'=>$id])->andWhere(['status'=>1])->one() ;
                if($modelServiceRequest)
                {
                    $modelServiceAssignment = $modelServiceRequest->fkServiceAssignment;
                    if($modelServiceAssignment)
                    {
                        $modelServiceAssignment->planned_date = isset($params['planned_date']) ? $params['planned_date'] : '';
                        $modelServiceAssignment->save(false);
                    }
                    else
                    {
                        $modelServiceAssignment = new ServiceAssignment;
                        $modelServiceAssignment->planned_date = isset($params['planned_date']) ? $params['planned_date'] : '';
                        $modelServiceAssignment->service_request_id = $modelServiceRequest->id;

                        $modelServiceAssignment->save(false);
                    }
                }
                $modelServiceRequest->service_id          = $params['service_id'];
                $modelServiceRequest->account_id_customer = $userId;
                $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
                $modelServiceRequest->remarks             = isset($params['remarks']) ? $params['remarks'] : '';
                $modelAccount = Account::find()->where(['id'=>$userId])->one();
                $modelCustomer = $modelAccount->fkCustomer;
                $modelServiceRequest->ward_id = isset($modelCustomer)?$modelCustomer->ward_id:'';
                $modelServiceRequest->lsgi_id = isset($modelCustomer->ward_id)?$modelCustomer->fkWard->lsgi_id:'';
                $modelServiceRequest->save(false);
                if(!$id){
                $modelPaymentRequest                      = new PaymentRequest;
                $modelPaymentRequest->account_id_customer = $userId;
                $modelPaymentRequest->requested_date  = date('Y-m-d H:i:s');
                $modelPaymentRequest->service_request_id = $modelServiceRequest->id;
                $modelPaymentRequest->amount = $modelPaymentRequest->getAmount($modelServiceRequest->id,$userId);
                $modelPaymentRequest->save(false);
                }
            }
            else
                {
                    $modelServiceRequest                      = new ServiceRequest;
                    $modelServiceRequest->service_id          = $params['service_id'];
                $modelServiceRequest->account_id_customer = $userId;
                $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
                $modelServiceRequest->remarks             = isset($params['remarks']) ? $params['remarks'] : '';
                $modelAccount = Account::find()->where(['id'=>$userId])->one();
                $modelCustomer = $modelAccount->fkCustomer;
                $modelServiceRequest->ward_id = isset($modelCustomer)?$modelCustomer->ward_id:'';
                $modelServiceRequest->lsgi_id = isset($modelCustomer->ward_id)?$modelCustomer->fkWard->lsgi_id:'';
                $modelServiceRequest->save(false);
                if(!$id){
                $modelPaymentRequest                      = new PaymentRequest;
                $modelPaymentRequest->account_id_customer = $userId;
                $modelPaymentRequest->requested_date  = date('Y-m-d H:i:s');
                $modelPaymentRequest->service_request_id = $modelServiceRequest->id;
                $modelPaymentRequest->amount = $modelPaymentRequest->getAmount($modelServiceRequest->id,$userId);
                $modelPaymentRequest->save(false);
                }
                }
             }
                $ret = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_id'         => $modelServiceRequest->service_id,
                    'requested_datetime' => $modelServiceRequest->requested_datetime,
                    'remarks'            => $modelServiceRequest->remarks
                ];
            }
            else
            {
                $msg   = ['service id is mandatory'];
                $error = ['service_id' => $msg];
                $ret   = ['errors' => $error];
            }
         break;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function actionComplaints($id=null)
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];

        while (true)
        {
            $params = Yii::$app->request->post();
            if (isset($params['complaint']))
            {
                if($id){
                $modelServiceRequest                            = ServiceRequest::find()->where(['id'=>$id])->andWhere(['status'=>1])->one() ;
                }

                else
                {
                    $modelServiceRequest                      = new ServiceRequest;
                }
                $serviceType  = 2;
                $modelService = Service::findByName($params['complaint'], $serviceType)->one();
                if ($modelService)
                {
                    $modelServiceRequest->service_id = $modelService->id;
                }
                else
                {
                    $modelService            = new Service;
                    $modelService->name      = $params['complaint'];
                    $modelService->type      = $serviceType;
                    $modelService->is_public = 0;
                    $modelService->save(false);
                    $modelServiceRequest->service_id = $modelService->id;
                }
                $modelServiceRequest->account_id_customer = $userId;
                // $modelServiceRequest->requested_datetime  = isset($params['requested_datetime']) ? $params['requested_datetime'] : '';
                $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
                $modelServiceRequest->remarks             = isset($params['remarks']) ? $params['remarks'] : '';
                $modelAccount = Account::find()->where(['id'=>$userId])->one();
                $modelCustomer = $modelAccount->fkCustomer;
                $modelServiceRequest->ward_id = isset($modelCustomer)?$modelCustomer->ward_id:'';
                $modelServiceRequest->lsgi_id = isset($modelCustomer->ward_id)?$modelCustomer->fkWard->lsgi_id:'';
                $modelServiceRequest->save(false);
                if(!$id)
                 ServiceRequest::complaint($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer);
                // if(!$id)
                // {
                //     $modelServiceAssignment = new ServiceAssignment;
                //     $modelServiceAssignment->service_request_id = $modelServiceRequest->id;
                //     $modelAccountAuthority = AccountAuthority::find()->where(['account_id_customer'=>$userId])->andWhere(['status'=>1])->one();
                //     if($modelAccountAuthority)
                //     {
                //         $modelServiceAssignment->account_id_gt = $modelAccountAuthority->account_id_gt;
                //     }
                //     $modelServiceAssignment->save(false);
                // }
                $ret = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_id'         => $modelServiceRequest->service_id,
                    'requested_datetime' => $modelServiceRequest->requested_datetime,
                    'remarks'            => $modelServiceRequest->remarks
                ];
            }
            else
            {
                $msg   = ['Complaint is mandatory'];
                $error = ['complaint' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }
    public function actionDelete($id)
    {
       $modelServiceRequest  = new ServiceRequest;
       $modelServiceRequest->deleteRequest($id);
       return $ret=[
       'status'=>'success'
       ];

    }
     public function actionRate()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];

        while (true)
        {
            $params = Yii::$app->request->post();
            if (isset($params['service_request_id']))
            {
                if(!isset($params['marked_value']))
                {
                $msg   = ['Marked value is mandatory'];
                $error = ['marked_value' => $msg];
                $ret   = ['errors' => $error];
                return $ret;
                }
                if(!isset($params['total_value']))
                {
                $msg   = ['Total value is mandatory'];
                $error = ['total_value' => $msg];
                $ret   = ['errors' => $error];
                return $ret;
                }
                $modelServiceRequest                            = ServiceRequest::find()->where(['id'=>$params['service_request_id']])->andWhere(['status'=>1])->one() ;
                $modelServiceRequest->marked_rating_value          = $params['marked_value'];
                $modelServiceRequest->total_rating_value          = $params['total_value'];
                $modelServiceRequest->save(false);
                if($modelServiceRequest->marked_rating_value)
                {
                        $modelEvaluationConfigCustomerRating = EvaluationConfigCustomerRating::find()->where(['rating_value'=>$modelServiceRequest->marked_rating_value])->andWhere(['status'=>1])->one();
                        if($modelEvaluationConfigCustomerRating&&$modelServiceRequest)
                        {
                            $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$modelServiceRequest->ward_id])->andWhere(['status'=>1])->one();
                            if($modelHksWard)
                            {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               {
                                 $qry = "SELECT max(performance_point) as performance_point FROM `evaluation_config_customer_rating` where status=1";
                                $command =  Yii::$app->db->createCommand($qry);
                                $data = $command->queryAll();
                                $point = $data[0];
                                $performance_point_max = $point['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$modelEvaluationConfigCustomerRating->performance_point;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$modelEvaluationConfigCustomerRating->performance_point; 
                                $modelServiceRequest->save(false);
                                $modelHks->save(false);
                               }
                            }
                           
                        }
                $modelLsgi = Lsgi::find()->where(['id'=>$modelServiceRequest->lsgi_id])->andWhere(['status'=>1])->one();
                            if($modelLsgi)
                                {
                                  $modelLsgi->last_rating_calculated_at = date('Y-m-d H:i:s');
                                  $modelLsgi->save(false);
                                }
                }
                $ret = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_id'         => $modelServiceRequest->service_id,
                    'is_cancelled'         => $modelServiceRequest->is_cancelled,
                    'marked_value'         => $modelServiceRequest->marked_rating_value,
                    'total_value'         => $modelServiceRequest->total_rating_value,
                    'requested_datetime' => $modelServiceRequest->requested_datetime,
                    'remarks'            => $modelServiceRequest->remarks
                ];
            }
            else
            {
                $msg   = ['service request id is mandatory'];
                $error = ['service_request_id' => $msg];
                $ret   = ['errors' => $error];
            }
         break;
        }

        return $ret;
    }


}
