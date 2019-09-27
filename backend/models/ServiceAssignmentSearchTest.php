<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ServiceRequest;

/**
 * ServiceRequestSearch represents the model behind the search form of `backend\models\ServiceRequest`.
 */
class ServiceAssignmentSearchTest extends ServiceRequestTest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at','service_request_id','account_id_gt','remarks','servicing_datetime','servicing_status_option_id','quantity','quality','door_status','lat','lng'], 'safe'],
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
    public function search($params,$id)
    {
        $query = ServiceAssignmentTest::find()->where(['service_assignment.status'=>1])->andWhere(['service_request_id'=>$id])
        ->orderby('id DESC');
        

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
            'service_id' => $this->service_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

   

        return $dataProvider;
    }
}
