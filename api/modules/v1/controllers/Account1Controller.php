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
use api\modules\v1\models\ServiceRequest;
use api\modules\v1\models\DeactivationRequest;
use api\modules\v1\models\WasteCategory;
use api\modules\v1\models\WasteCollectionMethod;
use yii\filters\AccessControl;
use api\modules\v1\components\AccessRule;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class Account1Controller extends ActiveController
{
    public $ROLE_GT = 'green-technician';
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
                        'actions' => ['account-id','pay-fee','deactivate','fee','waste-collection-method','change-waste-collection-method'],
                        'roles' => ['green-technician'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class'   => VerbFilter::className(),
            //     'actions' => [
            //         'delete' => ['POST']
            //     ]
            // ],
            
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
        $query     = Customer::getAllQuery();
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
    public function actionSurveyHistory(
        $keyword = null,
        $page = 1,
        $per_page = 30,
        $qr_set = null
    )
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $query     = Customer::getAllQuery();
        if ($keyword)
        {
            $query
                ->orWhere(['customer.id' => $keyword])
                ->orFilterWhere(['like', 'lead_person_name', $keyword]);
        }
        if ($qr_set == 0)
        {
            $query
                ->leftjoin('account', 'customer.id=account.customer_id')
                ->leftjoin('qr_code', 'qr_code.account_id=account.id')
                ->andWhere(['qr_code.account_id' => null]);
        }
        if ($qr_set == 1)
        {
            $query
                ->leftjoin('account', 'customer.id=account.customer_id')
                ->leftjoin('qr_code', 'qr_code.account_id=account.id')
                ->andWhere(['>', 'qr_code.account_id', 0]);
            // ->andWhere(['account.customer_id'=>$model->id])
        }
        $query->andWhere(['creator_account_id' => $userId]);
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

        ]);
        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
            $qrCodeSet    = $model->qrCodeSet($model->id);
            $ret[]        = [
                'id'                                                               => $model->id,
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
                'waste_collection_interval_id'                                     => $model->waste_collection_interval_id
            ];
        }

        return $ret;
    }

    /**
     * @param $code
     * @return mixed
     */
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
        $modelQrCode = QrCode::getAllQuery()->andWhere(['value' => $code])->one();
        if ($modelQrCode)
        {
            $ret = ['account_id' => $modelQrCode->account_id];

            return $ret;
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

        $query = AccountFee::getAllQuery();
        if ($account_id)
        {
            $query->andWhere(['account_id_customer' => $account_id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
            $amount = 0;
            foreach ($models as $model)
            {
                $amount = $amount + $model->amount_pending;
            }
            $ret = [
                'amount' => $amount
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
    public function actionPayFee()
    {
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
            if ($account_id)
            {
                $modelAccountFee = AccountFee::find()->where(['account_id_customer' => $account_id])
                                                     ->andWhere(['>', 'amount_pending', 0])->one();
                if ($customer && $modelAccountFee)
                {
                    $modelAccountFeePaid                      = new AccountFee;
                    $modelAccountFeePaid->amount_paid         = isset($params['amount_paid']) ? $params['amount_paid'] : '';
                    $modelAccountFeePaid->service_request_id  = $modelAccountFee->service_request_id;
                    $modelAccountFeePaid->account_id_customer = $account_id;
                    $modelAccountFeePaid->date                = date('Y-m-d');
                    $modelAccountFee->amount_pending          = $modelAccountFee->amount_pending - $params['amount_paid'];
                    $modelAccountFee->save(false);
                    $modelAccountFeePaid->save(false);
                    $models = AccountFee::find()->where(['account_id_customer' => $account_id])->andWhere(['status' => 1])->all();
                    $amount = 0;
                    foreach ($models as $model)
                    {
                        $amount = $amount + $model->amount_pending;
                    }
                    $ret = [
                        'amount' => $amount
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
      $types = ['bio-waste' => 'Bio waste','non-bio-waste' => 'Non bio waste','bio-medical'=>'Bio medical waste'];
      
         $query = WasteCollectionMethod::getAllQuery();
        if ($account_id)
        {
            $account = Account::find()->where(['id' => $account_id])->andWhere(['status' => 1])->one();
          if ($account)
          {
              $customer = Customer::find()->where(['id' => $account->customer_id])->andWhere(['status' => 1])->one();
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
              $ret[] = [
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
}
