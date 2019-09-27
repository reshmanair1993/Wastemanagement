<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Account;
use api\modules\v1\models\Service;
use api\modules\v1\models\ServiceAssignment;
use api\modules\v1\models\ServiceEnablerSettings;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\ServiceRequest;
use api\modules\v1\models\Customer;
use api\modules\v1\models\AccountService;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class MastersController extends ActiveController
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
    public function actionService(
        $page = 1,
        $per_page = 30,
        $keyword = null,
        $type = null,
        $is_special_services = null,
        $ml=null
    )
    {
        $modelUser  = Yii::$app->user->identity;
        $userId     = $modelUser->id;
        $types      = ['service' => 1, 'complaint' => 2];
        $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $query      = Service::getAllQuery()->andWhere(['service.status' => 1]);

        $modelServiceRequest = ServiceRequest::find()->select('service_id,service_request.id')->where(['account_id_customer' => $userId])->andWhere(['service_request.status' => 1]);
        $serviceIdExcluded = [];
       $dataAll = $modelServiceRequest->all();
       foreach ($dataAll as $value) {
         $modelAssignment = ServiceAssignment::find()->where(['service_request_id'=>$value->id])->andWhere(['status'=>1])->one();
         if(!$modelAssignment)
         {
            $serviceIdExcluded[] = $value->service_id;
         }
       }

        $modelServiceRequest->leftJoin('service_assignment', 'service_assignment.service_request_id=service_request.id')
                            ->andWhere(['service_assignment.servicing_status_option_id' => null])
                            ->andWhere(['service_assignment.status' => 1]);
        $data              = $modelServiceRequest->all();
        foreach ($data as $value)
        {
            $serviceIdExcluded[] = $value->service_id;
        }
        // print_r($serviceIdExcluded);die();
        if ($type)
        {
            $type = $types[$type];
        }
        if ($keyword && !$type)
        {
            $query->andWhere(['like', 'service.name', $keyword]);
        }
        if ($type && !$keyword)
        {
            $query->andWhere(['service.type' => $type]);
        }
        if ($keyword && $type)
        {
            $query->andWhere(['service.type' => $type])
                  ->andFilterWhere(['like', 'service.name', $keyword]);
        }
        if(isset($is_special_services))
        {
            if($is_special_services==1)
            $query->andWhere(['service.is_special_service' => 1]) ;
            if($is_special_services==0)
            $query->andWhere(['service.is_special_service' => 0]) ;
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false

        ]);

        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $image     = null;
            $serviceId = $model->id;
            if (in_array($serviceId, $serviceIdExcluded))
            {
                continue;
            }

            $modelImage = $model->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }
            if(isset($ml)&&$ml=='ml')
            {
                $name = $model->name_ml?$model->name_ml:$model->name;
            }
            else
            {
                $name = $model->name;
            }

            $ret[] = [
                'service_id'   => $model->id,
                'service_name' => $name,
                'image'        => $image,
                'ask_quantity' => $model->ask_waste_quantity ? $model->ask_waste_quantity : 0,
                'ask_quality'  => $model->ask_waste_quality ? $model->ask_waste_quality : 0
            ];
        }
           $modelAccount = Account::find()->where(['id'=>$userId])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
            $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->one();
            if($modelCustomer)
            {
                $modelServiceEnablerSettings = ServiceEnablerSettings::getAllQuery()->andWhere(['status' => 1]);
        $serviceEnablerSettingsDataProvider = new ActiveDataProvider([
            'query'      => $modelServiceEnablerSettings,
            'pagination' => false

        ]);
        $serviceEnablerSettingsModels = $serviceEnablerSettingsDataProvider->getModels();
        foreach ($serviceEnablerSettingsModels as $serviceEnablerSettingsModel) {
          $enableField = $serviceEnablerSettingsModel->customer_field;
          if($modelCustomer[$enableField]==$serviceEnablerSettingsModel->customer_field_value)
          {
            $modelImage = $serviceEnablerSettingsModel->fkService->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }
             if(isset($ml)&&$ml=='ml')
            {
                $name = $serviceEnablerSettingsModel->fkService->name_ml?$$serviceEnablerSettingsModel->fkService->name_ml:$serviceEnablerSettingsModel->fkService->name;
            }
            else
            {
                $name = $serviceEnablerSettingsModel->fkService->name;
            }
            $ret[] = [
                'service_id'   => $serviceEnablerSettingsModel->id,
                'service_name' => $name,
                'image'        => $image,
                'ask_quantity' => $serviceEnablerSettingsModel->fkService->ask_waste_quantity ? $serviceEnablerSettingsModel->fkService->ask_waste_quantity : 0,
                'ask_quality'  => $serviceEnablerSettingsModel->fkService->ask_waste_quality ? $serviceEnablerSettingsModel->fkService->ask_waste_quality : 0
            ];
          }
        }
            }
        }
       $ret = array_map("unserialize", array_unique(array_map("serialize", $ret)));
        return $ret = [
            'image_base' => $image_base,
            'items'      => $ret
        ];
    }



    public function actionServices(
        $page = 1,
        $per_page = 30,
        $keyword = null,
        $type = null,
        $is_special_services = null,
        $language=null
    )
    {
        $modelUser  = Yii::$app->user->identity;
        $userId     = $modelUser->id;
        $types      = ['service' => 1, 'complaint' => 2];
        $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $query      = Service::getAllQuery()->andWhere(['service.status' => 1]);
         $modelAccount = Account::find()->where(['id'=>$userId])->andWhere(['status'=>1])->one();
        $query      = Service::getAllQuery()->andWhere(['service.status' => 1]);
        if($modelAccount)
        {
            if($modelAccount->role!='cityzen')
            {
            $modelCustomer = $modelAccount->fkCustomer;
            if(isset($modelCustomer->building_type_id)&&$modelCustomer->building_type_id==1)
            {
                $query->andWhere(['service.is_residential'=>1]);
            }
            else
            {
               $query->andWhere(['service.is_non_residential'=>1]);  
            }
        }else
        {
            $query->andWhere(['service.is_cityzen'=>1]);
        }
        }
        $modelServiceRequest = ServiceRequest::find()->select('service_id,service_request.id')->where(['account_id_customer' => $userId])->andWhere(['service_request.status' => 1]);
        $serviceIdExcluded = [];
       $dataAll = $modelServiceRequest->all();
       foreach ($dataAll as $value) {
         $modelAssignment = ServiceAssignment::find()->where(['service_request_id'=>$value->id])->andWhere(['status'=>1])->one();
         if(!$modelAssignment)
         {
            $serviceIdExcluded[] = $value->service_id;
         }
       }

        $modelServiceRequest->leftJoin('service_assignment', 'service_assignment.service_request_id=service_request.id')
                            ->andWhere(['service_assignment.servicing_status_option_id' => null])
                            ->andWhere(['service_assignment.status' => 1]);
        $data              = $modelServiceRequest->all();
        foreach ($data as $value)
        {
            $serviceIdExcluded[] = $value->service_id;
        }
        // print_r($serviceIdExcluded);die();
        if ($type)
        {
            $type = $types[$type];
        }
        if ($keyword && !$type)
        {
            $query->andWhere(['like', 'service.name', $keyword]);
        }
        if ($type && !$keyword)
        {
            $query->andWhere(['service.type' => $type]);
        }
        if ($keyword && $type)
        {
            $query->andWhere(['service.type' => $type])
                  ->andFilterWhere(['like', 'service.name', $keyword]);
        }
        if(isset($is_special_services))
        {
            if($is_special_services==1)
            $query->andWhere(['service.is_special_service' => 1]) ;
            if($is_special_services==0)
            $query->andWhere(['service.is_special_service' => 0]) ;
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false

        ]);

        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $image     = null;
            $serviceId = $model->id;
            if (in_array($serviceId, $serviceIdExcluded))
            {
                continue;
            }

            $modelImage = $model->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }
            if(isset($language)&&$language=='ml')
            {
                $name = $model->name_ml?$model->name_ml:$model->name;
            }
            else
            {
                $name = $model->name;
            }
            $ret[] = [
                'service_id'   => $model->id,
                'service_name' => $name,
                'image'        => $image,
                'ask_quantity' => $model->ask_waste_quantity ? $model->ask_waste_quantity : 0,
                'ask_quality'  => $model->ask_waste_quality ? $model->ask_waste_quality : 0
            ];
        }
           $modelAccount = Account::find()->where(['id'=>$userId])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
            $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->one();
            if($modelCustomer)
            {
                $modelServiceEnablerSettings = ServiceEnablerSettings::getAllQuery()->andWhere(['service_enabler_settings.status' => 1]);
                if($type){
                $modelServiceEnablerSettings->leftJoin('service','service.id=service_enabler_settings.service_id')
                ->andWhere(['service.status' => 1])
                ->andWhere(['service_enabler_settings.status' => 1])
                ->andWhere(['service.type' => $type]);
            }
        $serviceEnablerSettingsDataProvider = new ActiveDataProvider([
            'query'      => $modelServiceEnablerSettings,
            'pagination' => false

        ]);
        $serviceEnablerSettingsModels = $serviceEnablerSettingsDataProvider->getModels();
        foreach ($serviceEnablerSettingsModels as $serviceEnablerSettingsModel) {
          $enableField = $serviceEnablerSettingsModel->customer_field;
          if($modelCustomer[$enableField]==$serviceEnablerSettingsModel->customer_field_value)
          {
            $modelImage = $serviceEnablerSettingsModel->fkService->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }
            if(isset($language)&&$language=='ml')
            {
                $name = $serviceEnablerSettingsModel->fkService->name_ml?$serviceEnablerSettingsModel->fkService->name_ml:$serviceEnablerSettingsModel->fkService->name;
            }
            else
            {
                $name = $serviceEnablerSettingsModel->fkService->name;
            }
            // $ret[] = [
            //     'service_id'   => $serviceEnablerSettingsModel->id,
            //     'service_name' => $name,
            //     'image'        => $image,
            //     'ask_quantity' => $serviceEnablerSettingsModel->fkService->ask_waste_quantity ? $serviceEnablerSettingsModel->fkService->ask_waste_quantity : 0,
            //     'ask_quality'  => $serviceEnablerSettingsModel->fkService->ask_waste_quality ? $serviceEnablerSettingsModel->fkService->ask_waste_quality : 0
            // ];
          }
        }
        }
        }
       $ret = array_map("unserialize", array_unique(array_map("serialize", $ret)));
       $excludeArray =[];
       if($modelAccount)
       {
        $modelAccountServices = AccountService::find()->where(['account_id'=>$userId]);
        if($type){
                $modelAccountServices->leftJoin('service','service.id=account_service.service_id')
                ->andWhere(['service.status' => 1])
                ->andWhere(['account_service.status' => 1])
                ->andWhere(['service.type' => $type]);
            }
        $modelAccountServices =$modelAccountServices->all();
        if($modelAccountServices)
        {
            foreach ($modelAccountServices as $modelAccountService) {
            $modelImage = $modelAccountService->fkService->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }
            // $excludeArray[] = [
            //     'service_id'   => $modelAccountService->id,
            //     'service_name' => $modelAccountService->fkService->name,
            //     'image'        => $image,
            //     'ask_quantity' => $modelAccountService->fkService->ask_waste_quantity ? $modelAccountService->fkService->ask_waste_quantity : 0,
            //     'ask_quality'  => $modelAccountService->fkService->ask_waste_quality ? $modelAccountService->fkService->ask_waste_quality : 0
            // ];

        }
       }
   }
   $diff = array_diff(array_map('json_encode', $ret), array_map('json_encode', $excludeArray));
$ret = array_map('json_decode', $diff);
        return $ret = [
            'image_base' => $image_base,
            'items'      => $ret
        ];
    }
}
