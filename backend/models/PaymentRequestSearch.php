<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PaymentRequest;

/**
 * PaymentRequestSearch represents the model behind the search form of `backend\models\PaymentRequest`.
 */
class PaymentRequestSearch extends PaymentRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'service_request_id', 'account_id_customer', 'is_closed', 'status'], 'integer'],
            [['amount'], 'number'],
            [['requested_date', 'created_at', 'modified_at'], 'safe'],
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
    public function search($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$from= null,$to=null)
    {
        $query = PaymentRequest::find()->where(['payment_request.status'=>1])->orderby('payment_request.id ASC');

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
        if($unit&&!$ward)
        {
            $wardIds =[];
            $wards = GreenActionUnitWard::find()->where(['green_action_unit_id'=>$unit])->all();
            if($wards)
            {
                foreach ($wards as $key => $value) {
                    $wardIds[]= $value->ward_id;
                }
                $ward = $wardIds;

            }
        }
        if($ward!=null||$lsgi!=null||$district!=null||$keyword!=null)
        {
             $query->leftjoin('account','account.id=payment_request.account_id_customer')
             ->leftjoin('customer','customer.id=account.customer_id');
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
            // $query->andWhere(['ward_id'=>$ward]);
            $query->andWhere(['ward_id'=>$ward]);
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
            $query->andWhere(['>=', 'payment_request.requested_date', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'payment_request.requested_date', $to]);
        }
        if($modelUser->role=='customer')
        {
            $query->andWhere(['payment_request.account_id_customer'=>$modelUser->id]);
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
            'amount' => $this->amount,
            'service_request_id' => $this->service_request_id,
            'account_id_customer' => $this->account_id_customer,
            'requested_date' => $this->requested_date,
            'is_closed' => $this->is_closed,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
}
