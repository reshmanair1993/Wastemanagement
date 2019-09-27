<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KitchenBinRequest;

/**
 * KitchenBinRequestSearch represents the model behind the search form of `backend\models\KitchenBinRequest`.
 */
class KitchenBinRequestSearch extends KitchenBinRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ward_id', 'ownership_of_house', 'status', 'approval_status'], 'integer'],
            [['house_owner_name', 'house_number', 'residence_association', 'association_number', 'contact_no', 'address', 'owner_name', 'contact_number_owner', 'created_at', 'modified_at'], 'safe'],
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
        $query = KitchenBinRequest::find()->where(['kitchen_bin_request.status'=>1])->orderby('kitchen_bin_request.id ASC');

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
            'ward_id' => $this->ward_id,
            'ownership_of_house' => $this->ownership_of_house,
            'status' => $this->status,
            'approval_status' => $this->approval_status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'house_owner_name', $this->house_owner_name])
            ->andFilterWhere(['like', 'house_number', $this->house_number])
            ->andFilterWhere(['like', 'residence_association', $this->residence_association])
            ->andFilterWhere(['like', 'association_number', $this->association_number])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'owner_name', $this->owner_name])
            ->andFilterWhere(['like', 'contact_number_owner', $this->contact_number_owner]);

        return $dataProvider;
    }
}
