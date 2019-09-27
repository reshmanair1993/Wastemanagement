<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Complaint;

/**
 * ComplaintSearch represents the model behind the search form of `backend\models\Complaint`.
 */
class ComplaintSearch extends Complaint
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'account_id_customer', 'account_id_gt', 'account_id_completed_by', 'status'], 'integer'],
            [['title', 'requested_date', 'servicing_date', 'remark', 'created_at', 'modified_at'], 'safe'],
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
        public function search($params,$keyword=null,$service=null,$gt=null,$from=null,$to=null)
    {
        $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d"):'';
       $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d"):'';
        $query = Complaint::find()->where(['complaint.status'=>1])->orderby('id DESC');
        if($keyword)
        {
            $query->leftjoin('customer','customer.id=complaint.account_id_customer')
            ->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        // if($service)
        // {
        //     $query->leftjoin('service','service.id=complaint.service_id')
        //     ->andWhere(['service.id'=>$service]);
        // }
         if($gt)
        {
            $query->leftjoin('account','account.id=complaint.account_id_gt')
            ->andWhere(['account.id'=> $gt]);
        }
        if(($from_date&&$to_date)){
        $query->andWhere(['>=', 'complaint.requested_date',$from_date])
        ->andWhere(['<=', 'complaint.requested_date',$to_date]);
      }
      if($from_date){
        $query->andWhere(['>=', 'complaint.requested_date',$from_date]);
      }
      if($to_date){
        $query->andWhere(['<=', 'complaint.requested_date',$to_date]);
      }

        // add conditions that should always apply here

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
            'account_id_gt' => $this->account_id_gt,
            'account_id_completed_by' => $this->account_id_completed_by,
            'requested_date' => $this->requested_date,
            'servicing_date' => $this->servicing_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
