<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\QrCode;
use api\modules\v1\models\Account;
use api\modules\v1\models\Customer;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\AccountFee;
use api\modules\v1\models\AccountService;
use api\modules\v1\models\ServiceRequest;
use api\modules\v1\models\ServiceAssignment;
use api\modules\v1\models\DeactivationRequest;
use api\modules\v1\models\WasteCategory;
use api\modules\v1\models\Gender;
use api\modules\v1\models\WasteCollectionMethod;
use api\modules\v1\models\CreditGenerationRequest;
use yii\filters\AccessControl;
use api\modules\v1\components\AccessRule;
use yii\web\UploadedFile;
use  api\modules\v1\models\Image;
use  api\modules\v1\models\AccountGt;
use  api\modules\v1\models\Payment;
use  api\modules\v1\models\PaymentRequest;
use  api\modules\v1\models\KitchenBinRequest;
use  backend\models\GreenActionUnit;
use  backend\models\ScheduleCustomer;
use  backend\models\Schedule;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ],
            'access' => [
                'class' => AccessControl::className(),
                 'only' => ['account-id','pay-fee','deactivate','fee','waste-collection-method','change-waste-collection-method'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['pay-fee','deactivate','fee','waste-collection-method','change-waste-collection-method'],
                        'roles' => ['green-technician','supervisor'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['account-id'],
                        'roles' => ['green-technician','supervisor','junior-health-inspector'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @var string
     */
    public $modelClass = '\api\modules\v1\models\Account';
    public function actionSurveysCount()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $query     = Customer::getAllQuery()->andWhere(['status' => 1]);
        $query->andWhere(['creator_account_id' => $userId]);
        $count = (int) $query->count();

        return [
            'count' => $count
        ];
    }


    /**
     * @return mixed
     */
    public function actionSettings()
    {
        $ret = [
            "lsgi_id"                     => null,
            'lsgi_block_id'               => null,
            'assembly_constituency_id'    => null,
            'district_id'                 => null,
            'parliament_constituency_id1' => null,
            'parliament_constituency_id2' => null
        ];
        $modelUser = Yii::$app->user->identity;
        if ($modelUser)
        {
            $modelLsgi = $modelUser->fkLsgi;
            if ($modelLsgi)
            {
                $ret['lsgi_id'] = $modelLsgi->id;
                $modelLsgiBlock = $modelLsgi->fkLsgiBlock;
                if ($modelLsgiBlock)
                {
                    $ret['lsgi_block_id']      = $modelLsgiBlock->id;
                    $modelAssemblyConstituency = $modelLsgiBlock->fkAssemblyConstituency;
                    if ($modelAssemblyConstituency)
                    {
                        $ret['assembly_constituency_id']    = $modelAssemblyConstituency->id;
                        $modelDistrict                      = $modelAssemblyConstituency->fkDistrict;
                        $ret['district_id']                 = $modelDistrict->id;
                        $ret['parliament_constituency_id1'] = $modelAssemblyConstituency->parliament_constituency_id_1;
                        $ret['parliament_constituency_id2'] = $modelAssemblyConstituency->parliament_constituency_id_2;
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function actionChangePassword()
    {
        $ret = [];

        while (true)
        {
            $id             = Yii::$app->user->id;
            $modelUser      = Account::findOne($id);
            $modelPerson    = $modelUser->fkPerson;
            $password       = null;
            $passwordRepeat = null;
            $oldPassword    = null;
            $passwordHash   = $modelUser->password_hash;

            $params = Yii::$app->request->post();

            $modelUser->setScenario("changeExistingPassword");

            $password       = isset($params['password']) ? $params['password'] : null;
            $passwordRepeat = isset($params['password_repeat']) ? $params['password_repeat'] : null;
            $oldPassword    = isset($params['old_password']) ? $params['old_password'] : null;
            if (null != $password && null != $passwordRepeat && null != $oldPassword)
            {
                if ($modelUser->validatePassword($oldPassword))
                {
                    if (null != $password && $password == $passwordRepeat)
                    {
                        $passwordHash             = Yii::$app->getSecurity()->generatePasswordHash($password);
                        $modelUser->password_hash = $passwordHash;
                        $modelUser->save(false);
                        $ret = [
                            'status' => 'success'
                        ];
                    }
                }
                else
                {
                    $msg   = ['Incorrect Old Password'];
                    $error = ['old_password' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
             elseif(null != $password && null != $passwordRepeat && null == $oldPassword)
            {
                if (null != $password && $password == $passwordRepeat)
                    {
                        $passwordHash             = Yii::$app->getSecurity()->generatePasswordHash($password);
                        $modelUser->password_hash = $passwordHash;
                        $modelUser->save(false);
                        $ret = [
                            'status' => 'success'
                        ];
                    }
            }
            else
            {
                $ret = $modelUser->validate() ? '' : ['errors' => $modelUser->errors];
            }
            break;
        }

        return $ret;
    }

    /**
     * @param $keyword
     * @param null $page
     * @param $per_page
     * @param $qr_set
     * @return mixed
     */
    // public function actionSurveyHistory(
    //     $keyword = null,
    //     $page = 1,
    //     $per_page = 30,
    //     $qr_set = null,
    //     $door_status=null
    // )
    // {
    //     $modelUser = Yii::$app->user->identity;
    //     $userId    = $modelUser->id;
    //     $query     = Customer::getAllQuery();
    //     if($qr_set){

    //     if (!$keyword&&$qr_set == 0)
    //     {
    //         $query
    //             ->leftjoin('account', 'customer.id=account.customer_id')
    //             ->leftjoin('qr_code', 'qr_code.account_id=account.id')
    //             ->andWhere(['qr_code.account_id' => null]);
    //     }
    //     if (!$keyword&&$qr_set == 1)
    //     {
    //         $query
    //             ->leftjoin('account', 'customer.id=account.customer_id')
    //             ->leftjoin('qr_code', 'qr_code.account_id=account.id')
    //             ->andWhere(['>', 'qr_code.account_id', 0]);
    //         // ->andWhere(['account.customer_id'=>$model->id])
    //     }
    // }
    //     if ($keyword&&!$qr_set)
    //     {
    //         $query
    //             ->leftjoin('account', 'customer.id=account.customer_id')
    //             ->andWhere(['account.id' => $keyword])
    //             ->orFilterWhere(['like', 'lead_person_name', $keyword]);
    //     }
    //     if ($keyword&&$qr_set)
    //     {

    //         if ($qr_set == 0)
    //     {
    //         $query
    //             ->leftjoin('account', 'customer.id=account.customer_id')
    //             ->leftjoin('qr_code', 'qr_code.account_id=account.id')
    //             ->andWhere(['qr_code.account_id' => null]);
    //     }
    //     if ($qr_set == 1)
    //     {
    //         $query
    //             ->leftjoin('account', 'customer.id=account.customer_id')
    //             ->leftjoin('qr_code', 'qr_code.account_id=account.id')
    //             ->andWhere(['>', 'qr_code.account_id', 0]);
    //         // ->andWhere(['account.customer_id'=>$model->id])
    //     }
    //         $query
    //             // ->leftjoin('account', 'customer.id=account.customer_id')
    //             ->andWhere(['account.id' => $keyword])
    //             ->orFilterWhere(['like', 'lead_person_name', $keyword]);
    //     }
    //     if(isset($door_status)){
    //     if($door_status==1)
    //     {
    //         $query->andWhere(['door_status'=>1]);
    //     }
    //     if($door_status==0)
    //     {
    //         $query->andWhere(['door_status'=>0]);
    //     }
    // }
    //     // if($modelUser->role=='surveyor'){
    //        $query->andWhere(['creator_account_id' => $userId]); 
    //     // }
    //     // if($modelUser->role=='supervisor'){
    //     //    $query->leftjoin('account_authority','account_authority.account_id_customer=account.id')
    //     //    ->andWhere(['account_authority.account_id_supervisor' => $userId]); 
    //     // }
    //     $dataProvider = new ActiveDataProvider([
    //         'query'      => $query,
    //         'pagination' => [
    //             'pageSize' => $per_page,
    //             'page'     => $page - 1
    //         ]

    //     ]);
    //     $models = $dataProvider->getModels();
    //     $ret    = [];
    //     foreach ($models as $model)
    //     {
    //         $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
    //         $qrCodeSet    = $model->qrCodeSet($model->id);
    //         $ret[]        = [
    //             'id'                                                               => $model->id,
    //             'customer_id'                                                               => $model->getFormattedCustomerId($model->id),
    //             'building_type'                                                    => $model->getBuildingType(),
    //             'ward_id'                                                          => $model->ward_id,
    //             'building_name'                                                    => $model->building_name,
    //             'building_number'                                                  => $model->building_number,
    //             'association_name'                                                 => $model->association_name,
    //             'association_number'                                               => $model->association_number,
    //             'customer_name'                                                    => $model->lead_person_name,
    //             'customer_phone'                                                   => $model->lead_person_phone,
    //             'address'                                                          => $model->address,
    //             'building_owner_name'                                              => $model->building_owner_name,
    //             'building_owner_phone'                                             => $model->building_owner_phone,
    //             'trading_type'                                                     => $model->getTradingType(),
    //             'shop_type'                                                        => $model->getShopType(),
    //             // 'image_id' => $modelAccount->getImageUrl(),
    //             'has_bio_waste'                                                    => $model->has_bio_waste,
    //             'has_non_bio_waste'                                                => $model->has_non_bio_waste,
    //             'has_disposible_waste'                                             => $model->has_disposible_waste,
    //             'lat'                                                              => $model->lat,
    //             'lng'                                                              => $model->lng,
    //             'fee_collection_interval'                                          => $model->getFeeCollectionInterval(),
    //             'has_bio_waste_management_facility'                                => $model->has_bio_waste_management_facility,
    //             'has_non_bio_waste_management_facility'                            => $model->has_non_bio_waste_management_facility,
    //             'bio_waste_management_facility_operational'                        => $model->bio_waste_management_facility_operational,
    //             'bio_waste_management_facility_repair_help_needed'                 => $model->bio_waste_management_facility_repair_help_needed,
    //             'bio_waste_collection_method'                                      => $model->getBioWasteCollectionMethod(),
    //             'bio_waste_collection_needed'                                      => $model->bio_waste_collection_needed,
    //             'non_bio_waste_collection_method'                                  => $model->getNonBioWasteCollectionMethod(),
    //             'has_terrace_farming_interest'                                     => $model->has_terrace_farming_interest,
    //             'terrace_farming_help_type'                                        => $model->getTerraceFarmingHelpType(),
    //             'door_status'                                                      => $model->door_status,
    //             'people_count'                                                     => $model->people_count,
    //             'house_adult_count'                                                => $model->house_adult_count,
    //             'house_children_count'                                             => $model->house_children_count,
    //             'market_visiters_count'                                            => $model->market_visiters_count,
    //             'seating_capacity'                                                 => $model->seating_capacity,
    //             'monthly_booking_count'                                            => $model->monthly_booking_count,
    //             'house_count'                                                      => $model->house_count,
    //             'public_place_type'                                                => $model->getPublicPlaceType(),
    //             'public_gathering_method'                                          => $model->getPublicGatheringMethod(),
    //             'is_programmes_happening'                                          => $model->is_programmes_happening,
    //             'public_place_area'                                                => $model->public_place_area,
    //             'office_type'                                                      => $model->getOfficeType(),
    //             'office_contact_person'                                            => $model->office_contact_person,
    //             'office_contact_person_designation'                                => $model->office_contact_person_designation,
    //             'qr_code_set'                                                      => $qrCodeSet ? 1 : 0,
    //             'daily_collection_needed_bio'                                      => $model->daily_collection_needed_bio,
    //             'shop_name'                                                        => $model->shop_name,
    //             'licence_no'                                                       => $model->licence_no,
    //             'employee_count'                                                   => $model->employee_count,
    //             'account_id'                                                       => $modelAccount ? $modelAccount->id : '',
    //             'parent_account_id'                                                => $modelAccount ? $modelAccount->parent_id : '',
    //             'space_available_for_bio_waste_management_facility'                => $model->space_available_for_bio_waste_management_facility,
    //             'help_needed_for_bio_waste_management_facility_construction'       => $model->help_needed_for_bio_waste_management_facility_construction,
    //             'building_in_use'                                                  => $model->building_in_use,
    //             'has_space_for_non_bio_waste_management_facility'                  => $model->has_space_for_non_bio_waste_management_facility,
    //             'space_available_for_non_bio_waste_management_facility'            => $model->space_available_for_non_bio_waste_management_facility,
    //             'has_interest_for_allotting_space_for_non_bio_management_facility' => $model->has_interest_for_allotting_space_for_non_bio_management_facility,
    //             'has_interest_in_bio_waste_management_facility'                    => $model->has_interest_in_bio_waste_management_facility,
    //             'office_type'                                                      => $model->office_type_id,
    //             'green_protocol_system_implemented'                                => $model->green_protocol_system_implemented,
    //             'bio_medical_waste_collection_facility'                            => $model->bio_medical_waste_collection_facility,
    //             'has_bio_medical_incinerator'                                      => $model->has_bio_medical_incinerator,
    //             'bio_medical_waste_collection_method'                              => $model->getBioMedicalWasteCollectionMethod(),
    //             'building_area'                                                    => $model->building_area,
    //             'daily_bio_waste_quantity'                                         => $model->daily_bio_waste_quantity,
    //             'lead_designation'                                                 => $model->lead_person_designation,
    //             'building_sub_type'                                                => $model->getBuildingSubType(),
    //             'administration_type'                                              => $model->getAdministrationType(),
    //             'public_program_count'                                             => $model->public_program_count,
    //             'has_interest_in_system_provided_bio_facility'                     => $model->has_interest_in_system_provided_bio_facility,
    //             'waste_collection_interval_id'                                     => $model->waste_collection_interval_id,
    //             'residential_association_id'                                       => $model->residential_association_id,
    //             'has_public_toilet'                                                => $model->has_public_toilet,
    //             'public_toilet_count'                                              => $model->public_toilet_count,
    //             'public_toilet_count_men'                                          => $model->public_toilet_count_men,
    //             'public_toilet_count_women'                                        => $model->public_toilet_count_women,
    //         ];
    //     }

    //     return $ret;
    // }

    /**
     * @param $code
     * @return mixed
     */
    // public function actionAccountId($code = null)
    // {
    //     $post   = Yii::$app->request->post();
    //     $params = ['code'];
    //     foreach ($params as $param)
    //     {
    //         if ($code)
    //         {
    //             $$param = $code;
    //         }
    //         else
    //         {
    //             Yii::$app->response->statusCode = 401;
    //             $errors                         = [
    //                 'errors' => [
    //                     $param => "$param is mandatory"
    //                 ]
    //             ];

    //             return $errors;
    //         }
    //     }
    //     $modelUser = Yii::$app->user->identity;
    //     $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code])->one();
    //     if ($modelQrCode)
    //     {
    //         $modelAccountGt = AccountGt::find()->where(['account_id_customer' =>$modelQrCode->account_id,'status' => 1])->one();
    //         if($modelAccountGt){
    //             if($modelUser->role=='green-technician')
    //             {
    //           if($modelUser->id == $modelAccountGt->account_id_gt){
    //           $ret = ['account_id' => $modelQrCode->account_id];
    //           return $ret;
    //           }
    //       }elseif($modelUser->role=='supervisor')
    //             {
    //           if($modelUser->id == $modelAccountGt->account_id_supervisor){
    //           $ret = ['account_id' => $modelQrCode->account_id];
    //           return $ret;
    //           }
    //       }
    //     }
    //     else
    //     {
    //       Yii::$app->response->statusCode = 401;
    //       $errors                         = [
    //         'errors' => [
    //             'code' => ['Unknown Customer']
    //         ]
    //     ];
    //     return $errors;
    //   }
    // }
    //     else
    //     {
    //         Yii::$app->response->statusCode = 401;
    //         $errors                         = [
    //             'errors' => [
    //                 'code' => ['Incorrect code']
    //             ]
    //         ];

    //         return $errors;
    //     }
    //   }
    // public function actionAccountId($code = null)
    // {
    //     $post   = Yii::$app->request->post();
    //     $params = ['code'];
    //     foreach ($params as $param)
    //     {
    //         if ($code)
    //         {
    //             $$param = $code;
    //         }
    //         else
    //         {
    //             Yii::$app->response->statusCode = 401;
    //             $errors                         = [
    //                 'errors' => [
    //                     $param => "$param is mandatory"
    //                 ]
    //             ];

    //             return $errors;
    //         }
    //     }
    //     $modelUser = Yii::$app->user->identity;
    //     $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code])->one();
    //     if ($modelQrCode)
    //     {
    //         $modelAccountGts = AccountGt::find()->where(['account_id_customer' =>$modelQrCode->account_id,'status' => 1])->all();
    //         if($modelAccountGts){
    //             if($modelUser->role=='green-technician')
    //             {
    //                 foreach ($modelAccountGts as $modelAccountGt) {
    //           if($modelUser->id == $modelAccountGt->account_id_gt){
    //           $ret = ['account_id' => $modelQrCode->account_id];
    //           return $ret;
    //           }
    //           }
    //       }elseif($modelUser->role=='supervisor')
    //             {
    //                 foreach ($modelAccountGts as $modelAccountGt) {
    //           if($modelUser->id == $modelAccountGt->account_id_supervisor){
    //           $ret = ['account_id' => $modelQrCode->account_id];
    //           return $ret;
    //           }
    //       }
    //     }
    // }
    //     else
    //     {
    //       Yii::$app->response->statusCode = 401;
    //       $errors                         = [
    //         'errors' => [
    //             'code' => ['Unknown Customer']
    //         ]
    //     ];
    //     return $errors;
    //   }
    // }
    //     else
    //     {
    //         Yii::$app->response->statusCode = 401;
    //         $errors                         = [
    //             'errors' => [
    //                 'code' => ['Incorrect code']
    //             ]
    //         ];

    //         return $errors;
    //     }
    //   }
      public function actionAccountId($code = null)
    {
        $post   = Yii::$app->request->post();
        $params = ['code'];
        foreach ($params as $param)
        {
            if ($code)
            {
                $$param = $code;
            }
            else
            {
                Yii::$app->response->statusCode = 401;
                $errors                         = [
                    'errors' => [
                        $param => "$param is mandatory"
                    ]
                ];

                return $errors;
            }
        }
        $modelUser = Yii::$app->user->identity;
        $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code])->one();
        if ($modelQrCode)
        {
            if($modelUser->role!='junior-health-inspector'){
            $modelAccountGts = AccountGt::find()->where(['account_id_customer' =>$modelQrCode->account_id,'status' => 1])->all();
            if($modelAccountGts){
                if($modelUser->role=='green-technician')
                {
                    foreach ($modelAccountGts as $modelAccountGt) {
              if($modelUser->id == $modelAccountGt->account_id_gt){
              $ret = ['account_id' => $modelQrCode->account_id];
              return $ret;
              }
              }
          }elseif($modelUser->role=='supervisor')
                {
                    foreach ($modelAccountGts as $modelAccountGt) {
              if($modelUser->id == $modelAccountGt->account_id_supervisor){
              $ret = ['account_id' => $modelQrCode->account_id];
              return $ret;
              }
          }
        }
    }
        else
        {
          Yii::$app->response->statusCode = 401;
          $errors                         = [
            'errors' => [
                'code' => ['Unknown Customer']
            ]
        ];
        return $errors;
      }
  } else{
        $account = Account::find()->where(['id' => $modelQrCode->account_id])->andWhere(['status' => 1])->one();
        if ($account)
        {
            $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
        }
        else
        {
                $msg   = ['Account id is invalid'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];

                return $ret;
        }
        if($customer->fkBuildingType->fkCategory->rate_type&&$customer->fkBuildingType->fkCategory->rate_type==1){
            $ret = ['account_id' => $modelQrCode->account_id];
            return $ret;
        }else
        {
            Yii::$app->response->statusCode = 401;
          $errors                         = [
            'errors' => [
                'code' => ['Unknown Customer']
            ]
        ];
        return $errors;
        }
        }
    }
        else
        {
            Yii::$app->response->statusCode = 401;
            $errors                         = [
                'errors' => [
                    'code' => ['Incorrect code']
                ]
            ];

            return $errors;
        }
      }

    /**
     * @param $account_id
     * @return mixed
     */
    public function actionDeactivate()
    {
        $params = Yii::$app->request->post();
        $account_id = isset($params['account_id'])?$params['account_id']:'';
        $ret       = [];
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $account   = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
        if ($account)
        {
            $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
        }
        while (true)
        {
            if ($account_id)
            {
                $modelDeactivateRequest = new DeactivationRequest;
                if ($customer)
                {
                    $modelDeactivateRequest->account_id_gt       = $userId;
                    $modelDeactivateRequest->account_id_customer = $account_id;
                    $modelDeactivateRequest->requested_datetime  = isset($params['requested_datetime']) ? $params['requested_datetime'] : '';
                    $modelDeactivateRequest->save(false);
                    $ret = [
                        'account_id' => $modelDeactivateRequest->account_id_customer
                        // 'account_id_gt'   => $modelDeactivateRequest->account_id_gt,
                        // 'requested_datetime'    => $modelDeactivateRequest->requested_datetime,
                    ];
                }
                else
                {
                    $msg   = ['Incorrect account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }

    /**
     * @param $account_id
     * @return mixed
     */
    public function actionFee($account_id = null)
    {

        $query = Payment::getAllQuery();
        if ($account_id)
        {
            $paymentRequest = PaymentRequest::find()->where(['account_id_customer'=>$account_id])->andWhere(['status'=>1])->andWhere(['is_closed'=>0])->all();
            $totalAmount = 0;
            if($paymentRequest)
            {
                foreach ($paymentRequest as $key => $value) {
                   $totalAmount = $totalAmount + $value->amount;
                }
            }
            $query->leftjoin('payment_request','payment_request.id=payment.payment_request_id')
            ->andWhere(['account_id_customer' => $account_id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
            $amount = 0;
            foreach ($models as $model)
            {
                $amount = $amount + $model->amount;
            }
            $ret = [
                'amount' => $totalAmount - $amount
            ];
        }
        else
        {
            $msg   = ['Account id is mandatory'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error];
        }

        return $ret;
    }

    /**
     * @param $account_id
     * @return mixed
     */
    // public function actionPayFee()
    // {
    //     $params = Yii::$app->request->post();
    //     $account_id = isset($params['account_id'])?$params['account_id']:'';
    //     $ret     = [];
    //     $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
    //     if ($account)
    //     {
    //         $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
    //     }
    //     while (true)
    //     {
    //         if ($account_id)
    //         {
    //             $modelAccountFee = AccountFee::find()->where(['account_id_customer' => $account_id])
    //                                                  ->andWhere(['>', 'amount_pending', 0])->one();
    //             if ($customer && $modelAccountFee)
    //             {
    //                 $modelAccountFeePaid                      = new AccountFee;
    //                 $modelAccountFeePaid->amount_paid         = isset($params['amount_paid']) ? $params['amount_paid'] : '';
    //                 $modelAccountFeePaid->service_request_id  = $modelAccountFee->service_request_id;
    //                 $modelAccountFeePaid->account_id_customer = $account_id;
    //                 $modelAccountFeePaid->date                = date('Y-m-d');
    //                 $modelAccountFee->amount_pending          = $modelAccountFee->amount_pending - $params['amount_paid'];
    //                 $modelAccountFee->save(false);
    //                 $modelAccountFeePaid->save(false);
    //                 $models = AccountFee::find()->where(['account_id_customer' => $account_id])->andWhere(['status' => 1])->all();
    //                 $amount = 0;
    //                 foreach ($models as $model)
    //                 {
    //                     $amount = $amount + $model->amount_pending;
    //                 }
    //                 $ret = [
    //                     'amount' => $amount
    //                 ];
    //             }
    //             else
    //             {
    //                 $msg   = ['Incorrect account id'];
    //                 $error = ['account_id' => $msg];
    //                 $ret   = ['errors' => $error];
    //             }
    //         }
    //         else
    //         {
    //             $msg   = ['Account id is mandatory'];
    //             $error = ['account_id' => $msg];
    //             $ret   = ['errors' => $error];
    //         }
    //         break;
    //     }

    //     return $ret;
    // }
    public function actionPayFee()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $params = Yii::$app->request->post();
        $account_id = isset($params['account_id'])?$params['account_id']:'';
        $ret     = [];
        $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
        if ($account)
        {
            $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
        }
        while (true)
        {
            if ($account_id && $customer)
            {
                if($customer->fkBuildingType->fkCategory->rate_type&&$customer->fkBuildingType->fkCategory->rate_type==1){
                 $qry = "SELECT sum(amount) as total_balance FROM `credit_generation_request` where status=1 and account_id=:account_id";
                    $command =  Yii::$app->db->createCommand($qry);
                    $command->bindParam(":account_id",$account_id);
                    $data = $command->queryAll();
                    $total = $data[0];
                    $total_amt = $total['total_balance']?$total['total_balance']:0;


                    $qry_new = "SELECT sum(payment.amount) as total_payment FROM `payment` left join payment_request on payment_request.id=payment.payment_request_id where payment.status=1 and payment_request.account_id_customer=:account_id and payment_request.status=1";
                    $command =  Yii::$app->db->createCommand($qry_new);
                    $command->bindParam(":account_id",$account_id);
                    $data = $command->queryAll();
                    $total = $data[0];
                    $total_amt_payment = $total['total_payment']?$total['total_payment']:0;
                    
                    if($total_amt<$total_amt_payment){
                   $failed = Payment::sendFailedMessage($account_id);
                } 
                if($total_amt<$total_amt_payment){
                    $msg   = ['Please recharge your account'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
                $modelPaymentRequest = PaymentRequest::find()->where(['account_id_customer' => $account_id])
                                                     ->andWhere(['is_closed'=>0])->one();
                if ($customer && $modelPaymentRequest)
                {
                    $modelPayment                      = new Payment;
                    $modelPayment->amount         = isset($params['amount_paid']) ? $params['amount_paid'] : '';
                    $modelPayment->payment_request_id  = $modelPaymentRequest->id;
                    $modelPayment->account_id_gt = $userId;
                    if($modelPayment->save(false))
                    {
                        $modelPayment->sendConfirmation($account_id,$modelPayment->amount);
                    }
                    $query = Payment::getAllQuery();
            $paymentRequest = PaymentRequest::find()->where(['account_id_customer'=>$account_id])->andWhere(['status'=>1])->andWhere(['is_closed'=>0])->all();
            $totalAmount = 0;
            if($paymentRequest)
            {
                foreach ($paymentRequest as $key => $value) {
                   $totalAmount = $totalAmount + $value->amount;
                }
            }
            $query->leftjoin('payment_request','payment_request.id=payment.payment_request_id')
            ->andWhere(['account_id_customer' => $account_id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
            $amount = 0;
            foreach ($models as $model)
            {
                $amount = $amount + $model->amount;
            }
                    $ret = [
                        'amount' => $totalAmount - $amount
                    ];
                }
                else
                {
                    $msg   = ['Incorrect account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }
    public function actionWasteCollectionMethod($account_id = null,$category=null)
    {
        $ret =[];
      $types = ['bio-waste' => 'Bio waste','non-bio-waste' => 'Non bio waste','bio-medical'=>'Bio medical waste'];

         $query = WasteCollectionMethod::getAllQuery();
        if ($account_id)
        {
            $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
          if ($account)
          {
              $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
              // print_r($customer);die();
          }
          else
            {
                $msg   = ['Invalid account id'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
                return $ret;
            }

          if($customer)
          {
            $query->where(['id' => $customer->bio_waste_collection_method_id])
            ->orWhere(['id' => $customer->non_bio_waste_collection_method_id])
            ->orWhere(['id' => $customer->bio_medical_waste_collection_method]);
            if($category)
         {
          $category = $types[$category];
          $modelWasteCategory = WasteCategory::getAllQuery()->andWhere(['name' => $category ])->one();
          $categoryId = $modelWasteCategory->id;
          $query->andWhere(['waste_category_id'=>$categoryId]);
         }
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
            $amount = 0;
            foreach ($models as $model)
            {
              $ret = [
                'id' => $model->id,
                'name' => $model->name,
            ];
            }

        }
        else
        {
            $msg   = ['Invalid account id'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error];
        }
      }
        else
        {
            $msg   = ['Account id is mandatory'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error];
        }

        return $ret;
    }
    public function actionChangeWasteCollectionMethod()
    {
        $params = Yii::$app->request->post();
        $account_id = isset($params['account_id'])?$params['account_id']:'';
        $category = isset($params['category'])?$params['category']:'';
      $types = ['bio-waste' => 'Bio waste','non-bio-waste' => 'Non bio waste','bio-medical'=>'Bio medical waste'];
      if($category){
      $category = $types[$category];
        $ret     = [];
        $bioWasteId = null;
        $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
        if ($account)
        {
            $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
        }
        while (true)
        {
            if ($account_id&&$customer)
            {
            $categories = WasteCategory::getAllQuery()->all();
         foreach($categories as $cat) {
           if($cat->name == 'Bio waste') {
             $bioWasteId = $cat->id;
           }
           if($cat->name == 'Non bio waste') {
             $nonBioWasteId = $cat->id;
           }
           if($cat->name == 'Bio medical waste') {
             $bioMedicalWasteId = $cat->id;
           }

         }

            if ($customer)
            {
            if($category =='Bio waste') {
  if(isset($params['waste_collection_method'])){
            $modelWasteCollectionMethod = WasteCollectionMethod::findByName($params['waste_collection_method'],$bioWasteId)->one();
            if($modelWasteCollectionMethod) {
              $customer->bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
              $new = 0;
            }
            else
           {
            $modelWasteCollectionMethod = new WasteCollectionMethod;
            $modelWasteCollectionMethod->name = $params['waste_collection_method'];
            $modelWasteCollectionMethod->waste_category_id = $bioWasteId;
            $modelWasteCollectionMethod->is_public = 0;
            $modelWasteCollectionMethod->save();
            $customer->bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
            $new = 1;
           }
           $customer->save(false);
           $ret = [
                'name' => $modelWasteCollectionMethod->name,
                'is_new'=>$new,
            ];
          }
          }
          if($category =='Non bio waste') {
            if(isset($params['waste_collection_method'])){
            $modelWasteCollectionMethod = WasteCollectionMethod::findByName($params['waste_collection_method'],$nonBioWasteId)->one();
            if($modelWasteCollectionMethod) {
              $customer->non_bio_waste_collection_method = $modelWasteCollectionMethod->id;
              $new = 0;
            }
            else
           {
            $modelWasteCollectionMethod = new WasteCollectionMethod;
            $modelWasteCollectionMethod->name = $params['waste_collection_method'];
            $modelWasteCollectionMethod->waste_category_id = $nonBioWasteId;
            $modelWasteCollectionMethod->is_public = 0;
            $modelWasteCollectionMethod->save();
            $customer->non_bio_waste_collection_method = $modelWasteCollectionMethod->id;
            $new = 1;
           }
           $customer->save(false);
           $ret[] = [
                'name' => $modelWasteCollectionMethod->name,
                'is_new'=>$new
            ];
          }
          }
          if($category =='Bio medical waste') {
            if(isset($params['waste_collection_method'])){
            $modelWasteCollectionMethod = WasteCollectionMethod::findByName($params['waste_collection_method'],$bioMedicalWasteId)->one();
            if($modelWasteCollectionMethod) {
              $customer->bio_medical_waste_collection_method = $modelWasteCollectionMethod->id;
              $new = 0;
            }
            else
           {
            $modelWasteCollectionMethod = new WasteCollectionMethod;
            $modelWasteCollectionMethod->name = $params['waste_collection_method'];
            $modelWasteCollectionMethod->waste_category_id = $bioMedicalWasteId;
            $modelWasteCollectionMethod->is_public = 0;
            $modelWasteCollectionMethod->save();
            $customer->bio_medical_waste_collection_method = $modelWasteCollectionMethod->id;
            $new = 1;
           }
           $customer->save(false);
           $ret[] = [
                'name' => $modelWasteCollectionMethod->name,
                'is_new'=>$new,
            ];
          }
          }
                }
                else
                {
                    $msg   = ['Incorrect account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }
    }
    else
            {
                $msg   = ['Category  is mandatory'];
                $error = ['category' => $msg];
                $ret   = ['errors' => $error];
            }

        return $ret;
    }
    public function actionUpdateProfile() {
      $ret = [];

      while(true) {
        $id = Yii::$app->user->id;
        $modelUser = Account::findOne($id);
        $modelPerson = $modelUser->fkPerson;
        if(!$modelPerson) break;

        $name = $modelPerson->first_name;
        $email = $modelPerson->email;
        $address = $modelPerson->address;
        $phone1 = $modelPerson->phone1;
        $gender = $modelPerson->fkGender['name'];
        $dob = $modelPerson->dob;
        $params = Yii::$app->request->post();
        $modelImage = new Image;
        $photo = $_FILES;
        $images = UploadedFile::getInstanceByName('photo');
        $modelImageSaveId = $modelImage->uploadAndSave($images);
         $image = $modelPerson->image_id?$modelPerson->image_id:"";

        $name = isset($params['name'])?$params['name']:$name;
        $email = isset($params['email'])?$params['email']:$email;
        $address = isset($params['address'])?$params['address']:$address;
        $phone1 = isset($params['phone'])?$params['phone']:$phone1;
        $gender = isset($params['gender'])?$params['gender']:$gender;
        $dob = isset($params['dob'])?$params['dob']:$dob;
        $dob = date('Y-m-d', strtotime($dob));

        $modelGender = Gender::find()->where(['name'=> $gender])->one();
        $gender = $modelGender?$modelGender->id:null;
        $modelPerson->fk_gender = $gender;

        $modelPerson->first_name = $name;
        $modelPerson->dob = $dob;
        $modelPerson->phone1 = $phone1;
        $modelPerson->address = $address;
        $personOk = $modelPerson->validate();
        if(!($personOk))
        break;
    if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
          $modelPerson->image_id = $modelImageSaveId;
        }
        else
        {
          $modelPerson->image_id = $image;
        }
        if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
          $modelUser->image_id = $modelImageSaveId;
          $modelUser->save(false);
        }
        else
        {
          $modelUser->image_id = $image;
          $modelUser->save(false);
        }
        $modelPerson->save(false);
        $ret = [
          'account_id'=> $modelUser->id,
          'name'=> $modelPerson->first_name,
          'phone'=> $modelPerson->phone1,
          'email'=> $modelPerson->email,
          'dob'=> date('d-m-Y', strtotime($modelPerson->dob)),
          'gender'=> $modelPerson->getGender(),
        ];
        break;
      }
      $errors =$modelPerson->errors;

      if($errors) $ret = $errors;
      return $ret;
    }
    public function actionGetProfile() {
    $base_url    = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
      $ret = [];
      $id = Yii::$app->user->id;
      $name = null;
      $phone1 = null;
      $email = null;
      $gender = null;
      $dob = null;
      $address = null;
      $image = null;

      $modelUser = Account::findOne($id);
      $modelPerson = $modelUser->fkPerson;
      if($modelPerson) {
        $name = isset($modelPerson->first_name)?$modelPerson->first_name:null;
        $phone1 = isset($modelPerson->phone1)?$modelPerson->phone1:null;
        $email = isset($modelPerson->email)?$modelPerson->email:null;
        $gender = $modelPerson->fk_gender?$modelPerson->getGender():null;
        $dob = $modelPerson->dob;
         $image = $modelUser->image_id?$modelUser->getImageUrl():null;
      }
      return $ret = [
          'base_url'=>$base_url,
          'account_id'=> $modelUser->id,
          'username'=> $modelUser->username,
          'name'=> $name,
          'phone'=> $phone1,
          'email'=> $email,
          'dob'=> $dob?date('d-m-Y', strtotime($modelPerson->dob)):'',
          'gender'=> $gender,
          'profilePic'=> $image,
          'lsgi'=>[
           'id'=>$modelUser->fkLsgi?$modelUser->fkLsgi->id:null,
           'name'=>$modelUser->fkLsgi?$modelUser->fkLsgi->name:null,
           ]
        ];;
    }
    public function actionSurveyHistory(
        $keyword = null,
        $page = 1,
        $per_page = 30,
        $qr_set = null,
        $door_status=null
    )
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $query     = Customer::getAllQuery();

        if($qr_set||$keyword||$modelUser->role=='supervisor')
        {
            $query->leftjoin('account', 'customer.id=account.customer_id');
            if($qr_set){
            if($qr_set==1)
            {
                $query->leftjoin('qr_code', 'qr_code.account_id=account.id')
                ->andWhere(['>', 'qr_code.account_id', 0]);
            }
            if($qr_set==0)
            {
                $query->leftjoin('qr_code', 'qr_code.account_id=account.id')
                ->andWhere(['qr_code.account_id' => null]);
            }
        }
            if($keyword)
            {
                $query
                ->andWhere(['account.id' => $keyword])
                ->orFilterWhere(['like', 'lead_person_name', $keyword]);
            }
            if($modelUser->role=='supervisor'){
           $query->leftjoin('account_authority','account_authority.account_id_customer=account.id')
           ->andWhere(['account_authority.account_id_supervisor' => $userId]); 
        }
        }
        if(isset($door_status)){
            if($door_status==1)
            {
                $query->andWhere(['door_status'=>1]);
            }
            if($door_status==0)
            {
                $query->andWhere(['door_status'=>0]);
            }
        }
        if($modelUser->role=='surveyor'){
           $query->andWhere(['creator_account_id' => $userId]); 
        }
        
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

        ]);
        $models = $dataProvider->getModels();
        $countDataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false

        ]);
    //     $dataCount = $countDataProvider->getCount();
    //     if($dataCount>0){
    //     $next = $dataCount/$per_page;
    //     if($next>1)
    //     {
    //         $has_next = 1;
    //     }

    //     else
    //     {
    //         $has_next =0;
    //     }
    // }
    // else
    //     {
    //         $has_next =0;
    //     }


        $ret    = [];
        foreach ($models as $model)
        {
            $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
            $qrCodeSet    = $model->qrCodeSet($model->id);
            $ret[]        = [
                'id'                                                               => $model->id,
                'customer_id'                                                               => $model->getFormattedCustomerId($model->id),
                'building_type'                                                    => $model->getBuildingType(),
                'ward_id'                                                          => $model->ward_id,
                'building_name'                                                    => $model->building_name,
                'building_number'                                                  => $model->building_number,
                'association_name'                                                 => $model->association_name,
                'association_number'                                               => $model->association_number,
                'customer_name'                                                    => $model->lead_person_name,
                'customer_phone'                                                   => $model->lead_person_phone,
                'address'                                                          => $model->address,
                'building_owner_name'                                              => $model->building_owner_name,
                'building_owner_phone'                                             => $model->building_owner_phone,
                'trading_type'                                                     => $model->getTradingType(),
                'shop_type'                                                        => $model->getShopType(),
                // 'image_id' => $modelAccount->getImageUrl(),
                'has_bio_waste'                                                    => $model->has_bio_waste,
                'has_non_bio_waste'                                                => $model->has_non_bio_waste,
                'has_disposible_waste'                                             => $model->has_disposible_waste,
                'lat'                                                              => $model->lat,
                'lng'                                                              => $model->lng,
                'fee_collection_interval'                                          => $model->getFeeCollectionInterval(),
                'has_bio_waste_management_facility'                                => $model->has_bio_waste_management_facility,
                'has_non_bio_waste_management_facility'                            => $model->has_non_bio_waste_management_facility,
                'bio_waste_management_facility_operational'                        => $model->bio_waste_management_facility_operational,
                'bio_waste_management_facility_repair_help_needed'                 => $model->bio_waste_management_facility_repair_help_needed,
                'bio_waste_collection_method'                                      => $model->getBioWasteCollectionMethod(),
                'bio_waste_collection_needed'                                      => $model->bio_waste_collection_needed,
                'non_bio_waste_collection_method'                                  => $model->getNonBioWasteCollectionMethod(),
                'has_terrace_farming_interest'                                     => $model->has_terrace_farming_interest,
                'terrace_farming_help_type'                                        => $model->getTerraceFarmingHelpType(),
                'door_status'                                                      => $model->door_status,
                'people_count'                                                     => $model->people_count,
                'house_adult_count'                                                => $model->house_adult_count,
                'house_children_count'                                             => $model->house_children_count,
                'market_visiters_count'                                            => $model->market_visiters_count,
                'seating_capacity'                                                 => $model->seating_capacity,
                'monthly_booking_count'                                            => $model->monthly_booking_count,
                'house_count'                                                      => $model->house_count,
                'public_place_type'                                                => $model->getPublicPlaceType(),
                'public_gathering_method'                                          => $model->getPublicGatheringMethod(),
                'is_programmes_happening'                                          => $model->is_programmes_happening,
                'public_place_area'                                                => $model->public_place_area,
                'office_type'                                                      => $model->getOfficeType(),
                'office_contact_person'                                            => $model->office_contact_person,
                'office_contact_person_designation'                                => $model->office_contact_person_designation,
                'qr_code_set'                                                      => $qrCodeSet ? 1 : 0,
                'daily_collection_needed_bio'                                      => $model->daily_collection_needed_bio,
                'shop_name'                                                        => $model->shop_name,
                'licence_no'                                                       => $model->licence_no,
                'employee_count'                                                   => $model->employee_count,
                'account_id'                                                       => $modelAccount ? $modelAccount->id : '',
                'parent_account_id'                                                => $modelAccount ? $modelAccount->parent_id : '',
                'space_available_for_bio_waste_management_facility'                => $model->space_available_for_bio_waste_management_facility,
                'help_needed_for_bio_waste_management_facility_construction'       => $model->help_needed_for_bio_waste_management_facility_construction,
                'building_in_use'                                                  => $model->building_in_use,
                'has_space_for_non_bio_waste_management_facility'                  => $model->has_space_for_non_bio_waste_management_facility,
                'space_available_for_non_bio_waste_management_facility'            => $model->space_available_for_non_bio_waste_management_facility,
                'has_interest_for_allotting_space_for_non_bio_management_facility' => $model->has_interest_for_allotting_space_for_non_bio_management_facility,
                'has_interest_in_bio_waste_management_facility'                    => $model->has_interest_in_bio_waste_management_facility,
                'office_type'                                                      => $model->office_type_id,
                'green_protocol_system_implemented'                                => $model->green_protocol_system_implemented,
                'bio_medical_waste_collection_facility'                            => $model->bio_medical_waste_collection_facility,
                'has_bio_medical_incinerator'                                      => $model->has_bio_medical_incinerator,
                'bio_medical_waste_collection_method'                              => $model->getBioMedicalWasteCollectionMethod(),
                'building_area'                                                    => $model->building_area,
                'daily_bio_waste_quantity'                                         => $model->daily_bio_waste_quantity,
                'lead_designation'                                                 => $model->lead_person_designation,
                'building_sub_type'                                                => $model->getBuildingSubType(),
                'administration_type'                                              => $model->getAdministrationType(),
                'public_program_count'                                             => $model->public_program_count,
                'has_interest_in_system_provided_bio_facility'                     => $model->has_interest_in_system_provided_bio_facility,
                'waste_collection_interval_id'                                     => $model->waste_collection_interval_id,
                'residential_association_id'                                       => $model->residential_association_id,
                'has_public_toilet'                                                => $model->has_public_toilet,
                'public_toilet_count'                                              => $model->public_toilet_count,
                'public_toilet_count_men'                                          => $model->public_toilet_count_men,
                'public_toilet_count_women'                                        => $model->public_toilet_count_women,
                // 'has_next'=>$has_next,
            ];
        }

        return $ret;
    }
    public function actionKitchenBinRequest()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $params = Yii::$app->request->post();
        $account_id = isset($params['account_id'])?$params['account_id']:'';
        $ret     = [];
        
        while (true)
        {
            if ($account_id)
            {
                $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
                if ($account)
                {
                    $modelKitchenBinRequest = new KitchenBinRequest; 
                    $modelKitchenBinRequest->account_id_customer = $account_id; 
                    $modelKitchenBinRequest->account_id_requested_by = $userId; 
                    $modelKitchenBinRequest->requested_at = date('Y-m-d H:i:s'); 
                    $modelKitchenBinRequest->save(false); 
                    $ret = [
                        'account_id' => $account_id
                    ];
                }
                else
                {
                    $msg   = ['Incorrect account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }
    public function actionDashboard()
    {$ret = null;
        $scheduleCustomerCountList = 0;
        $lsgi     = null;
        $district = null;
        $ward     = null;
        $unit     = null;
        $gt       = null;
        $agency   = null;

         $pendingComplaintsCustomer= null;
         $totalComplaintsCustomer= null;
         $pendingServiceCustomer= null;
         $totalServiceCustomer= null;
           
        $modelUser    = Yii::$app->user->identity;
        $userRole     = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if (isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if (isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if (isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if (isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if (isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if (isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }

        $modelCustomer = Customer::find()->select([
            'customer.id'])->where(['customer.status' => 1]);
        // if ($unit&&$userRole != 'supervisor')
        if($ward)
        {
            $modelCustomer = $modelCustomer->andWhere(['customer.ward_id'=>$ward]);
        }
        if ($unit)
        {
           $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }
            $modelCustomer->leftJoin('green_action_unit_ward', 'customer.ward_id=green_action_unit_ward.ward_id')
                          ->leftJoin('green_action_unit', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                          // ->leftJoin('account', 'account.green_action_unit_id=green_action_unit.id')
                          ->leftjoin('building_type','building_type.id=customer.building_type_id')
            ->andWhere(['building_type.residence_category_id'=>$category])
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit])
                          ->andWhere(['green_action_unit.id' => $unit])
                          ->groupby('customer.id');
        }
        if ($userRole == 'supervisor'||$userRole == 'green-technician')
        {
            $modelCustomer
                ->leftJoin('account', 'account.customer_id=customer.id')
                ->leftJoin('account_authority', 'account_authority.account_id_customer=account.id')
                ->andWhere(['account_authority.status' => 1])
                ->andWhere(['account.status' => 1])
                ->groupby('customer.id');
                if($userRole == 'supervisor')
                {
                    $modelCustomer->andWhere(['account_authority.account_id_supervisor' => $modelUser->id]);
                }
                if($userRole == 'green-technician')
                {
                    $modelCustomer->andWhere(['account_authority.account_id_gt' => $modelUser->id]);
                }
        }
        $modelCustomer = $modelCustomer->all();
        $customerCount = count($modelCustomer);

        $query = ServiceAssignment::getAllQuery()->leftJoin('service_request', 'service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                                                 ->andWhere(['service.type' => 1])
                                                 ->andWhere(['service.status'=>1])
                                                 ->andWhere(['service_request.status'=>1]);
        if ($unit)
        {
            $query->leftJoin('green_action_unit_ward', 'service_request.ward_id=green_action_unit_ward.ward_id')
                  ->andWhere(['green_action_unit_ward.green_action_unit_id' => $unit])
                  ->andWhere(['green_action_unit_ward.status' => 1]);
        }
        if($userRole=='green-technician')
        {
            $query->andWhere(['service_assignment.account_id_gt'=>$modelUser->id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>false
        ]);
        $models         = $dataProvider->getModels();
        $pendingService = 0;
        foreach ($models as $model)
        {
            if ($model->servicing_status_option_id == null)
            {
                $pendingService = $pendingService + 1;
            }
        }
        $pendingquery = ServiceAssignment::getAllQuery()->leftJoin('service_request', 'service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                                                        ->andWhere(['service.type' => 2])
                                                        ->andWhere(['service.status'=>1])
                                                 ->andWhere(['service_request.status'=>1]);
        if ($unit)
        {
            $pendingquery->leftJoin('green_action_unit_ward', 'service_request.ward_id=green_action_unit_ward.ward_id')
                         ->andWhere(['green_action_unit_ward.green_action_unit_id' => $unit])
                         ->andWhere(['green_action_unit_ward.status' => 1]);
        }
         if ($userRole == 'green-technician')
        {
            $pendingquery->andWhere(['service_assignment.account_id_gt' => $modelUser->id])
                  ;
        }
        $pendingDataProvider = new ActiveDataProvider([
            'query' => $pendingquery,
            'pagination'=>false,
        ]);
        $pendingComplaints = 0;
        $models            = $pendingDataProvider->getModels();
        foreach ($models as $model)
        {
            if ($model->servicing_status_option_id == null)
            {
                $pendingComplaints = $pendingComplaints + 1;
            }
        }
if($userRole=='supervisor'){
        $planEnabledCustomers = AccountService::find()->where(['account_service.status'=>1])->leftjoin('account_authority','account_authority.account_id_customer=account_service.account_id')
        ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id])
        ->andWhere(['account_authority.status'=>1])
         ->groupby('account_authority.account_id_customer')
        ;
         $planEnabledCustomersDataProvider = new ActiveDataProvider([
            'query' => $planEnabledCustomers,
            'pagination'=>false
        ]);
        $planEnabledCustomersCount = 0;
        $models            = $planEnabledCustomersDataProvider->getModels();
        foreach ($models as $model)
        {
                $planEnabledCustomersCount = $planEnabledCustomersCount + 1;
        }
    }
        $date = date('Y-m-d');
      $dateNext = date('Y-m-d', strtotime($date));
      $dayofweek = date('w', strtotime($dateNext)) + 1;
      $dayofDate = date('d', strtotime($dateNext));
      $scheduleIds = [];
      $modelSchedule = Schedule::find()
      ->leftJoin('schedule_customer','schedule_customer.schedule_id=schedule.id')
       // ->where(['schedule.green_action_unit_id'=>$hksId]) 
      ->where(['schedule_customer.status'=>1])
      ->andWhere(['schedule.status'=>1])
      ->andWhere(['>','schedule.service_id',0])
      ->groupBy(['id'])
      ->all(); 
      foreach ($modelSchedule as $key => $value) {
        if($value->type==1)
        {
          if($value->week_day ==$dayofweek)
          {
            $scheduleIds[] = $value->id;
          }
        }elseif($value->type==2)
        {
          if($value->month_day ==$dayofDate)
          {
            $scheduleIds[] = $value->id;
          }

        }
        elseif($value->type==3)
        {
          if($value->date ==$dateNext)
          {
            $scheduleIds[] = $value->id;
          }

        }
        
      } 
      $scheduleIds = array_unique($scheduleIds); 
      foreach ($scheduleIds as $key => $scheduleId) {
        $scheduleCustomerCount = ScheduleCustomer::find()->where(['schedule_id'=>$scheduleId]);
        $scheduleCustomerCountDataProvider = new ActiveDataProvider([
            'query' => $scheduleCustomerCount,'pagination'=>false,
        ]);
        $scheduleCustomerCountList = 0;
        $models            = $scheduleCustomerCountDataProvider->getModels();
        foreach ($models as $model)
        {
                $scheduleCustomerCountList = $scheduleCustomerCountList + 1;
        }
      }
        if($modelUser->role=='supervisor'){

        $ret = [
                        'customers_count' => $customerCount,
                        'pending_service'    => $pendingService,
                        'pending_complaints' => $pendingComplaints,
                        'plan_enabled_customers_count' => $planEnabledCustomersCount,
                    ];
                }
                if($modelUser->role=='green-technician'){

        $ret = [
                        'customers_count' => $customerCount,
                        'schedule_customer_count' => $scheduleCustomerCountList,
                        'pending_service'    => $pendingService,
                        'pending_complaints' => $pendingComplaints,
                        // 'plan_enabled_customers_count' => $planEnabledCustomersCount,
                    ];
                }
                return $ret;
    }
    public function actionCreditGenerationRequest()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $params = Yii::$app->request->post();
        $account_id = isset($params['account_id'])?$params['account_id']:'';
        $amount = isset($params['amount'])?$params['amount']:'';
        $ret     = [];
        $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
        if ($account)
        {
            $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
        }
        while (true)
        {
            if ($account_id)
            {
        if (!$amount)
          {
                $msg   = ['Amount is mandatory'];
                $error = ['amount' => $msg];
                $ret   = ['errors' => $error];
                return $ret;
          }
                if ($customer)
                {
                    // $balance = 0;
                    // $modelBalances = CreditGenerationRequest::find()->where(['account_id'=>$account_id])->andWhere(['is_approved'=>1])->all();
                    // foreach ($modelBalances as $modelBalance) {
                    //    $balance = $balance + $modelBalance->balance;
                    // }
                    $modelCreditGenerationRequest=  new CreditGenerationRequest;
                    $modelCreditGenerationRequest->account_id=  $account_id;
                    $modelCreditGenerationRequest->amount=  $amount;
                    // $modelCreditGenerationRequest->balance = $amount;
                    $modelCreditGenerationRequest->save(false);
                    $ret = [
                        'id' => $modelCreditGenerationRequest->id,
                        'account_id' => $modelCreditGenerationRequest->account_id,
                        'amount' => $modelCreditGenerationRequest->amount,
                    ];
                   
                }
                else
                {
                    $msg   = ['Incorrect account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }
    public function actionApproveCreditGenerationRequest()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $params = Yii::$app->request->post();
        $account_id = isset($params['account_id'])?$params['account_id']:'';
        $id = isset($params['id'])?$params['id']:'';
        $ret     = [];
        if(!$account_id)
        {
            $msg   = ['Account id is mandatory'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error]; 
            return $ret;
        }
        if(!$id)
        {
            $msg   = ['Request id is mandatory'];
            $error = ['id' => $msg];
            $ret   = ['errors' => $error]; 
            return $ret;
        }
        if($account_id&&$id)
        {
            $modelCreditGenerationRequest = CreditGenerationRequest::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
            if($modelCreditGenerationRequest)
            {
                $modelCreditGenerationRequest->is_approved = isset($params['is_approved'])?$params['is_approved']:0;
                $modelCreditGenerationRequest->payment_status = isset($params['payment_status'])?$params['payment_status']:'';
                $modelCreditGenerationRequest->save(false);
                if($modelCreditGenerationRequest->payment_status==1)
                {
                    $status = 'Success';
                }elseif($modelCreditGenerationRequest->payment_status==1)
                {
                    $status = 'Failure';
                }
                else
                {
                    $status = null;
                }
                $ret = [
                        'id' => $modelCreditGenerationRequest->id,
                        'account_id' => $modelCreditGenerationRequest->account_id,
                        'is_approved' => $modelCreditGenerationRequest->is_approved,
                        'payment_status' => $status,
                    ];
                return $ret;  
            }
            else
            {
                $msg   = ['Request id is not valid'];
                $error = ['id' => $msg];
                $ret   = ['errors' => $error];
                return $ret;
            }
        }
    }
}
