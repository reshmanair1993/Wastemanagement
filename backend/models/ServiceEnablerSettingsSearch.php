<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ServiceEnablerSettings;

/**
 * ServiceEnablerSettingsSearch represents the model behind the search form of `backend\models\ServiceEnablerSettings`.
 */
class ServiceEnablerSettingsSearch extends ServiceEnablerSettings
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'service_id', 'status'], 'integer'],
            [['customer_field', 'customer_field_value', 'created_at', 'modified_at'], 'safe'],
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
    public function search($params,$id=null)
    {
        $query = ServiceEnablerSettings::find()->where(['status'=>1])->orderby('id DESC');
        if($id)
        {
            $query->andWhere(['service_id'=>$id]);
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
            'service_id' => $this->service_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'customer_field', $this->customer_field])
            ->andFilterWhere(['like', 'customer_field_value', $this->customer_field_value]);

        return $dataProvider;
    }
}
