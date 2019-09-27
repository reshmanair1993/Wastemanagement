<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AccountServiceRequest;

/**
 * AccountServiceRequestSearch represents the model behind the search form of `backend\models\AccountServiceRequest`.
 */
class AccountServiceRequestSearch extends AccountServiceRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'service_id', 'request_type', 'status', 'is_approved', 'account_id_requested_by', 'account_id_approved_by'], 'integer'],
            [['requested_at', 'approval_status_changed_at', 'created_at', 'modified_at'], 'safe'],
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
    public function search($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$from= null,$to=null,$type=null,$non_residential=null,$status=null)
    {
        $unit = null;
        $agency = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
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
        $query = AccountServiceRequest::find()
        ->leftjoin('account','account.id=account_service_request.account_id')
             ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['account_service_request.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['customer.status'=>1])
        ->orderby('account_service_request.id DESC');
        if($modelUser->role=='customer')
        {
            $query->andWhere(['account_service_request.account_id'=>$modelUser->id]);
        }

        if($modelUser->role=='junior-health-inspector')
        {
            $query->andWhere(['account_service_request.account_id_requested_by'=>$modelUser->id]);
        }

       if(($ward!=null||$lsgi!=null||$district!=null||$keyword!=null||$unit)&&$modelUser->role!='junior-health-inspector')
        {
              if($unit!=null)
        {
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
            $query->andWhere(['customer.ward_id'=>$ward]);
        }
        if($lsgi!=null||$district!=null)
        {
            $query
            ->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
            if($lsgi!=null)
            {
                $query->andWhere(['lsgi.id'=>$lsgi]);
            }
            if($district!=null)
        {
            $query
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        }
        }
        if($from!=null)
        {
            $query->andWhere(['>=', 'account_service_request.requested_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'account_service_request.requested_at', $to]);
        }
        if($type!=null){
        if($type==0)
        {
            $query->andWhere(['request_type'=>0]);
        }
        if($type==1)
        {
            $query->andWhere(['request_type'=>1]);
        }
        if($type==2)
        {
            $query->andWhere(['request_type'=>2]);
        }
    }else
    {
        $query->andWhere(['not',['request_type'=>2]]);
    }
    if($non_residential==1)
    {
        $query
        ->andWhere(['account_service_request.is_jhi_approved'=>1])
        ->andWhere(['not', ['account_service_request.service_estimate' => null]]) ;
    }
    else
    {
        $query
        ->andWhere(['account_service_request.service_estimate'=>null]);
    }
    if($status&&$status!=null)
    {
        if($status==1)
        $query->andWhere(['account_service_request.is_approved'=>1]);
    elseif($status==2)
        $query->andWhere(['account_service_request.is_approved'=>0]);
    }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'account_id' => $this->account_id,
            'service_id' => $this->service_id,
            'request_type' => $this->request_type,
            'status' => $this->status,
            'is_approved' => $this->is_approved,
            'requested_at' => $this->requested_at,
            'approval_status_changed_at' => $this->approval_status_changed_at,
            'account_id_requested_by' => $this->account_id_requested_by,
            'account_id_approved_by' => $this->account_id_approved_by,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
     public function agreement($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$from= null,$to=null,$type=null,$non_residential=null,$status=null,$agreement_status=null)
    {
        $unit = null;
        $agency = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
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
        $query = AccountServiceRequest::find()->where(['account_service_request.status'=>1])
        ->andWhere(['account_service_request.is_approved'=>1])
        ->orderby('account_service_request.id ASC');
        if($modelUser->role=='customer')
        {
            $query->andWhere(['account_service_request.account_id'=>$modelUser->id]);
        }

       if($ward!=null||$lsgi!=null||$district!=null||$keyword!=null||$unit)
        {
             $query->leftjoin('account','account.id=account_service_request.account_id')
             ->leftjoin('customer','customer.id=account.customer_id');
              if($unit!=null)
        {
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
            $query->andWhere(['customer.ward_id'=>$ward]);
        }
        if($lsgi!=null||$district!=null)
        {
            $query
            ->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
            if($lsgi!=null)
            {
                $query->andWhere(['lsgi.id'=>$lsgi]);
            }
            if($district!=null)
        {
            $query
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        }
        }
        if($from!=null)
        {
            $query->andWhere(['>=', 'account_service_request.requested_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'account_service_request.requested_at', $to]);
        }
        if($type!=null){
        if($type==0)
        {
            $query->andWhere(['request_type'=>0]);
        }
        if($type==1)
        {
            $query->andWhere(['request_type'=>1]);
        }
        if($type==2)
        {
            $query->andWhere(['request_type'=>2]);
        }
    }else
    {
        $query->andWhere(['not',['request_type'=>2]]);
    }
    if($non_residential==1)
    {
        $query->andWhere(['not', ['account_service_request.service_estimate' => null]]);
    }else
    {
        $query
        ->andWhere(['account_service_request.service_estimate'=>null]);
    }
    if($agreement_status==1)
    {
        $query->andWhere(['account_service_request.is_agreement_done'=>1]);
    }else
    {
        $query
        ->andWhere(['account_service_request.is_agreement_done'=>0]);
    }
    // if($status&&$status!=null)
    // {
    //     if($status==1)
    //     $query->andWhere(['account_service_request.is_approved'=>1]);
    // elseif($status==2)
    //     $query->andWhere(['account_service_request.is_approved'=>0]);
    // }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'account_id' => $this->account_id,
            'service_id' => $this->service_id,
            'request_type' => $this->request_type,
            'status' => $this->status,
            // 'is_approved' => $this->is_approved,
            'requested_at' => $this->requested_at,
            'approval_status_changed_at' => $this->approval_status_changed_at,
            'account_id_requested_by' => $this->account_id_requested_by,
            'account_id_approved_by' => $this->account_id_approved_by,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
}
