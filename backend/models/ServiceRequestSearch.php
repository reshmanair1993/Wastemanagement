<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ServiceRequest;

/**
 * ServiceRequestSearch represents the model behind the search form of `backend\models\ServiceRequest`.
 */
class ServiceRequestSearch extends ServiceRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'service_id', 'account_id_customer', 'status'], 'integer'],
            [['requested_datetime', 'created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$keyword=null,$service=null,$gt=null,$from=null,$to=null,$type=null,$status=null,$ward=null,$newParams=null,$is_special_service=null)
    {
        $supervisor = null;
        $unit = null;
        $agency = null;
        $lsgi = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
        $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d"):'';
       $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d"):'';
        $query = ServiceRequest::find()->where(['service_request.status'=>1])
        ->leftJoin('service','service.id=service_request.service_id')
        ->orderby('id DESC');
         if(isset($is_special_service)){
        if($is_special_service==1)
        {
            $query->andWhere(['is_special_service'=>1]);
        }
        }
        if($modelUser->role=='customer')
        {
            $query->andWhere(['service_request.account_id_customer'=>$modelUser->id]);
        }
        if($type)
        {
          $query->andWhere(['service.type'=>$type]);
        }
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      // $unit = $modelUser->green_action_unit_id;
    }
    if($supervisor)
    {
      $query
      ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer')
      ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
    }
        if($keyword||$ward||$unit||$lsgi)
        {
            $query->leftjoin('account','account.id=service_request.account_id_customer')
            ->leftjoin('customer','customer.id=account.customer_id');
            if($lsgi!=null)
            {
              $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            -> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($keyword){
               $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
            }
            if($ward!=null)
            {
              // $query->andWhere(['customer.ward_id'=>$ward]);
              $query->andWhere(['service_request.ward_id'=>$ward]);
            }
                if($unit!=null)
        {
            // $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            // ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
            
            $modelGreenActionUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            if($modelGreenActionUnit->residence_category_id!=3){
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }else
        {
             $query->leftjoin('green_action_unit_service','green_action_unit_service.service_id=service_request.service_id')
            ->andWhere(['green_action_unit_service.green_action_unit_id'=>$unit]);
        }
        }
        //     if($unit!=null)
        // {
        //     // $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
        //     // ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        //     $modelGreenActionUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
        //     if($modelGreenActionUnit->residence_category_id!=3){
        //     $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
        //     ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        // }else
        // {
        //      $query->leftjoin('green_action_unit_service','green_action_unit_service.service_id=service_request.service_id')
        //     ->andWhere(['green_action_unit_service.green_action_unit_id'=>$unit]);
        // }
        // }
            
        }
        if($service)
        {
            $query
            ->andWhere(['service.id'=>$service]);
        }
        if(($from_date&&$to_date)){
        $query->andWhere(['>=', 'service_request.requested_datetime',$from_date])
        ->andWhere(['<=', 'service_request.requested_datetime',$to_date]);
      }
      if($from_date){
        $query->andWhere(['>=', 'service_request.requested_datetime',$from_date]);
      }
      if($to_date){
        $query->andWhere(['<=', 'service_request.requested_datetime',$to_date]);
      }
      if($status)
      {
        if($status==1)
        {
          $query->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
          ->andWhere(['>','service_assignment.servicing_status_option_id',0]);
        }
        if($status==2)
        {
          $query->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
          ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
      }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
          'pageSize' => 50, 
      // 'params' =>  $newParams
        ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'service_id' => $this->service_id,
            'account_id_customer' => $this->account_id_customer,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

   

        return $dataProvider;
    }
    public function collectedQuality($params,$keyword=null,$service=null,$gt=null,$from=null,$to=null,$type=null,$status=null,$ward=null,$waste_type=null)
    {
        $supervisor = null;
        $unit = null;
        $agency = null;
        $lsgi = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
        $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d"):'';
       $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d"):'';
        $query = ServiceRequest::find()->where(['service_request.status'=>1])
        ->leftJoin('service','service.id=service_request.service_id')
        ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
        
        ->orderby('id DESC');
        // print_r($waste_type);die();
        if($waste_type==1)
        {
          $query->andWhere(['>','service_assignment.quality',0]);
        }
        if($waste_type==2)
        {
          $query->andWhere(['>','service_assignment.quantity',0]);
        }
        if($modelUser->role=='customer')
        {
            $query->andWhere(['service_request.account_id_customer'=>$modelUser->id]);
        }
        if($type)
        {
          $query->andWhere(['service.type'=>$type]);
        }
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      // $unit = $modelUser->green_action_unit_id;
    }
    if($supervisor)
    {
      $query
      ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer')
      ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
    }
        if($keyword||$ward||$unit||$lsgi)
        {
            $query->leftjoin('customer','customer.id=service_request.account_id_customer');
            if($lsgi!=null)
            {
              $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            -> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($keyword){
               $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
            }
            if($ward!=null)
            {
              // $query->andWhere(['customer.ward_id'=>$ward]);
              $query->andWhere(['service_request.ward_id'=>$ward]);
            }
            if($unit!=null)
        {
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
            
        }
        if($service)
        {
            $query
            ->andWhere(['service.id'=>$service]);
        }
        if(($from_date&&$to_date)){
        $query->andWhere(['>=', 'service_request.requested_datetime',$from_date])
        ->andWhere(['<=', 'service_request.requested_datetime',$to_date]);
      }
      if($from_date){
        $query->andWhere(['>=', 'service_request.requested_datetime',$from_date]);
      }
      if($to_date){
        $query->andWhere(['<=', 'service_request.requested_datetime',$to_date]);
      }
      if($status)
      {
        if($status==1)
        {
          $query
          ->andWhere(['>','service_assignment.servicing_status_option_id',0]);
        }
        if($status==2)
        {
          $query
          ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
      }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
          'pageSize' => 50, 
      // 'params' =>  $newParams
        ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'service_id' => $this->service_id,
            'account_id_customer' => $this->account_id_customer,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

   

        return $dataProvider;
    }
    
}
