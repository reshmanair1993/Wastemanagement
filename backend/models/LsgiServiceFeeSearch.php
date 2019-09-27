<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LsgiServiceFee;

/**
 * LsgiServiceFeeSearch represents the model behind the search form of `backend\models\LsgiServiceFee`.
 */
class LsgiServiceFeeSearch extends LsgiServiceFee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lsgi_id', 'service_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'modified_at'], 'safe'],
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
    public function search($params)
    {
        $query = LsgiServiceFee::find();

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
            'lsgi_id' => $this->lsgi_id,
            'service_id' => $this->service_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
}
