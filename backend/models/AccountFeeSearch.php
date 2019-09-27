<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AccountFee;

/**
 * AccountFeeSearch represents the model behind the search form of `backend\models\AccountFee`.
 */
class AccountFeeSearch extends AccountFee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'account_id_customer', 'service_request_id', 'status'], 'integer'],
            [['amount_paid', 'amount_pending'], 'number'],
            [['date', 'created_at', 'modified_at'], 'safe'],
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
        $query = AccountFee::find()->where(['account_fee.status'=>1])->orderby('account_fee.id ASC');

        // add conditions that should always apply here
        if($ward!=null||$lsgi!=null||$district!=null||$keyword!=null)
        {
             $query->leftjoin('account','account.id=account_fee.account_id_customer')
             ->leftjoin('customer','customer.id=account.customer_id');
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
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
            $query->andWhere(['>=', 'account_fee.date', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'account_fee.date', $to]);
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
            'account_id_customer' => $this->account_id_customer,
            'service_request_id' => $this->service_request_id,
            'amount_paid' => $this->amount_paid,
            'amount_pending' => $this->amount_pending,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
